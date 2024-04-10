<?php

namespace Wolfgang\Model;

//PHP
use ArrayObject;

//Wolfgang
use Wolfgang\SQL\Statement\DML\SelectStatement;
use Wolfgang\Interfaces\ORM\IQueryBuilder;
use Wolfgang\Interfaces\Model\IModelList;
use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;
use Wolfgang\Model\Manager as ModelManager;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\Model\IBridgeModel;
use Wolfgang\Database\DriverManager;
use Wolfgang\Exceptions\MethodNotImplementedException;
use Wolfgang\Exceptions\InvalidStateException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class ModelList extends Component implements \Iterator , IModelList , \Countable , IQueryBuilder , \ArrayAccess {
	/**
	 *
	 * @var ArrayObject
	 */
	private $objects;

	/**
	 *
	 * @var array
	 */
	private $ids = [ ];

	/**
	 *
	 * @var array
	 */
	private $select_options;

	/**
	 *
	 * @var int
	 */
	private $total_matches = 0;

	/**
	 *
	 * @var IStatement
	 */
	private $statement;

	/**
	 *
	 * @var string
	 */
	private $unit_class;

	/**
	 *
	 * @var string
	 */
	private $loaded = false;

	/**
	 *
	 * @var integer
	 */
	private $position = 0;

	/**
	 *
	 * @var IModel
	 */
	private $unit_class_instance;

	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->objects = new \ArrayObject( array () );

		$this->determineUnitClass();

		$table = $this->getUnitClassInstance()->getTable();
		$this->statement = new SelectStatement( $table );

		if ( ($this->getUnitClassInstance() instanceof IBridgeModel) ) {
			$bridge_column_names = $this->getUnitClassInstance()->getBridgeColumnNames();

			if ( ! $bridge_column_names ) {
				throw new InvalidStateException( "Could not determine bridge column names for table '{$table->getName()}'" );
			}

			foreach ( $bridge_column_names as $column_name ) {
				$this->statement->addSelectColumn( $column_name );
			}
		} else {
			$this->statement->addSelectColumn( "{$table->getName()}.id" );
		}

		return $this;
	}

	/**
	 *
	 * @return ModelList A reference to this instance
	 */
	public function findAll ( ):ModelList {
		$this->limit( 1000000000000 );
		return $this;
	}

	/**
	 */
	private function getObjectsFromStatement ( ): void {
		$connection = DriverManager::getInstance()->getConnection( $this->getUnitClassInstance()->getTable()->getSchema()->getDsn() );

		$records = $connection->exec( $this->getStatement() );
		$result = $connection->exec( "SELECT FOUND_ROWS() as total_matches;" );
		$result = $result->current();
		$this->setTotalMatches( $result[ 'total_matches' ] );
		$unit_class = $this->getUnitClass();
		$unit_class_instance = $this->getUnitClassInstance();
		$bridge_model = ($unit_class_instance instanceof IBridgeModel);

		foreach ( $records as $record ) {
			$id = null;

			if ( $bridge_model ) {
				$id = array ();

				foreach ( $unit_class_instance->getBridgeColumnNames() as $column_name ) {
					$id[] = $record[ $column_name ];
				}

				$id = implode( '_', $id );
			} else {
				$id = $record[ 'id' ];
			}

			$this->ids[] = $id;
			$object = $unit_class::findById( $id );

			if ( ! $object ) {
				throw new InvalidStateException( "Could not find model record with id '{$id}'" );
			}

			$this->append( $object );
		}

		$this->loaded = true;
	}

	/**
	 * Adds an additional object to this list
	 *
	 * @access private
	 * @param IModel $object
	 * @throws InvalidArgumentException
	 * @return void
	 */
	private function append ( IModel $model ) {
		if ( ! ($model instanceof $this->unit_class) ) {
			throw new InvalidArgumentException( "Invalid argument provided to add to list." );
		}

		$this->objects->append( $model );
	}

	/**
	 *
	 * @param int $total_matches
	 */
	private function setTotalMatches ( int $total_matches ) {
		$this->total_matches = $total_matches;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Model\IModelList::getIds()
	 */
	public function getIds ( ): array {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		return $this->ids;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Model\IModelList::getTotalMatches()
	 */
	public function getTotalMatches ( ): int {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		return $this->total_matches;
	}

	/**
	 * Calls a method on each object within this list and returns a reference to this instance
	 *
	 * @param callable $function This method will always return true in the case that an exception
	 *        is not thrown in the process.
	 */
	public function each ( callable $function ) {
		foreach ( $this as $object ) {
			$function( $object );
		}
		return $this;
	}

	/**
	 * Deletes all the objects within this list
	 *
	 * @return ModelList The instance of this model list 
	 * for method chaining, unless an exception is thrown within the process
	 */
	public function delete ( ):ModelList {
		$this->each( function ( IModel $model ) {
			$model->delete();
		} );
		return $this;
	}

	/**
	 * Saves all the objects within this list
	 *
	 * @return ModelList The instance of this model list 
	 * for method chaining, unless an exception is thrown within the process
	 */
	public function save():ModelList{
		$this->each( function ( IModel $model ) {
			$model->save();
		} );
		return $this;
	}

	/**
	 * Filters the objects within this list by a set of respective fields and values.
	 *
	 * @param mixed $field The field or fields to filter the objects by
	 * @param mixed $value The value or values for the specified fields / attributes to filter this
	 *        objects of the list by
	 * @return boolean False if the $field or $value params are empty true otherwise
	 */
	public function filter ( $field, $value ) {
		if ( (! empty( $field ) && ! empty( $value )) )
			return false;

		if ( (is_array( $field ) && is_array( $value )) ) {
			foreach ( $field as $key => $_f ) {
				$this->filter( $_f, $value[ $key ] );
			}
		} else if ( ! is_array( $field ) && is_array( $value ) ) {
			foreach ( $value as $_v ) {
				$this->filter( $field, $_v );
			}
		} else {
			foreach ( $this as $key => $object ) {
				if ( $object->{$field} == $value ) {
					$this->getObjects()->offsetUnset( $key );
				}
			}
		}

		return true;
	}

	/**
	 * Creates and array from the set of objects that are within this list that represents a set of
	 * options that can be used in an HTML dropdown select field
	 *
	 * @return array An array of options with the ids of the objects as keys and either title title,
	 *         name or label attribute of the objects as values.
	 */
	public function getSelectOptions ( ) {
		if ( empty( $this->select_options ) ) {
			if ( ! $this->loaded ) {
				$this->getObjectsFromStatement();
			}

			$this->select_options = array ();

			foreach ( $this as $object ) {
				$value = null;

				if ( method_exists( $object, 'getName' ) ) {
					$value = $object->getName();
				}

				if ( empty( $value ) ) {
					$value = @$object->label;
				}

				if ( empty( $value ) ) {
					$value = @$object->title;
				}

				if ( empty( $value ) ) {
					$value = $object->name;
				}

				if ( empty( $value ) ) {
					$value = @$object->value;
				}

				if ( empty( $value ) ) {
					if ( method_exists( $object, 'getLabel' ) ) {
						$value = $object->getLabel();
					}
				}

				if ( ! $value ) {
					$value = 'Undetermined';
				}

				$this->select_options[ $object->getId() ] = $value;
			}
		}

		return $this->select_options;
	}

	/**
	 * Gets an instance of the ArrayObject set for this instance
	 *
	 * @return ArrayObject The ArrayObject set of items loaded for this instance
	 */
	public function getObjects ( ):ArrayObject {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		return $this->objects;
	}

	/**
	 *
	 * @access private
	 * @return ModelList
	 */
	private function determineUnitClass ( ): ModelList {
		$this->unit_class = str_replace( "List", "", get_class( $this ) );
		$this->unit_class_instance = ModelManager::getInstance()->create( $this->unit_class );
		return $this;
	}

	/**
	 * Gets the unit class for this class / instance
	 *
	 * @return string $unit_class The unit class for this class / instance
	 */
	private function getUnitClass ( ) {
		return $this->unit_class;
	}

	/**
	 * Determines whether or not a provded value, as an attribute, is an allowed attribute of the
	 * unit class this list class corresponds with
	 *
	 * @return boolean TRUE if the atribute is an allowed attribute of the unit class, FALSE
	 *         otherwise
	 */
	public function isUnitClassAllowedAttribute ( $attribute ) {
		return $this->getUnitClassInstance()->getTable()->isField( $attribute );
	}

	/**
	 * Gets the instance of the unit class for this list class instance
	 *
	 * @return IModel A reference to the instance of the unit class instance for this list class
	 */
	public function getUnitClassInstance ( ): IModel {
		return $this->unit_class_instance;
	}

	/**
	 *
	 * @return IStatement
	 */
	public function getStatement ( ): IStatement {
		return $this->statement;
	}

	public function first(){
		return $this->offsetGet(0);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Countable::count()
	 */
	public function count ( ):int {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		return $this->getObjects()->count();
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::current()
	 */
	public function current ( ):mixed {
		return $this->objects->offsetGet( $this->position );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::key()
	 */
	public function key ( ):mixed {
		return $this->position;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::next()
	 */
	public function next ( ):void {
		$this->position ++;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::rewind()
	 */
	public function rewind ( ):void {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		$this->position = 0;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Iterator::valid()
	 */
	public function valid ( ):bool {
		return $this->objects->offsetExists( $this->position ) ? true : false;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \ArrayAccess::offsetExists()
	 */
	public function offsetExists ( $offset ):bool {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		return $this->objects->offsetExists( $offset );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \ArrayAccess::offsetGet()
	 */
	public function offsetGet ( $offset ):mixed {
		if ( ! $this->loaded ) {
			$this->getObjectsFromStatement();
		}

		if ( ! $this->offsetExists( $offset ) ) {
			return null;
		}

		return $this->objects->offsetGet( $offset );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \ArrayAccess::offsetSet()
	 */
	public function offsetSet ( $offset, $value ):void {
		$this->objects->offsetSet($offset, $value);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \ArrayAccess::offsetUnset()
	 */
	public function offsetUnset ( $offset ):void {
		throw new MethodNotImplementedException();
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::where()
	 */
	public function where ( $where ): IQueryBuilder {
		$this->statement->where( $where );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::andWhere()
	 */
	public function andWhere ( $where ): IQueryBuilder {
		$this->statement->andWhere( $where );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::orWhere()
	 */
	public function orWhere ( $where ): IQueryBuilder {
		$this->statement->orWhere( $where );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::groupBy()
	 */
	public function groupBy ( array $group_by ): IQueryBuilder {
		$this->statement->groupBy( $group_by );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::addGroupBy()
	 */
	public function addGroupBy ( array $group_by ): IQueryBuilder {
		$this->statement->addGroupBy( $group_by );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::having()
	 */
	public function having ( $having ): IQueryBuilder {
		$this->statement->having( $having );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::andHaving()
	 */
	public function andHaving ( $having ): IQueryBuilder {
		$this->statement->andHaving( $having );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::orHaving()
	 */
	public function orHaving ( $having ): IQueryBuilder {
		$this->statement->orHaving( $having );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::orderBy()
	 */
	public function orderBy ( $expression, $order = IOrderByClause::ORDER_ASC): IQueryBuilder {
		$this->statement->orderBy( $expression, $order );
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\IQueryBuilder::addOrderBy()
	 */
	public function addOrderBy ( $expression, $order = IOrderByClause::ORDER_ASC): IQueryBuilder {
		$this->statement->addOrderBy( $expression, $order );
		return $this;
	}

	/**
	 *
	 * @param int|array $limit
	 * @return \Wolfgang\Model\ModelList
	 */
	public function limit ( $limit ): IQueryBuilder {
		$this->statement->limit( $limit );
		return $this;
	}
}
