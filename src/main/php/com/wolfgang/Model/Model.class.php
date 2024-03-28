<?php

namespace Wolfgang\Model;

// PHP
use ReflectionException;

// Wolfgang
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Date\DateTime;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Exceptions\Model\Exception as ModelException;
use Wolfgang\Traits\TMarshallable;
use Wolfgang\Util\Inflector;
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\IllegalOperationException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\ORM\SchemaManager;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;
use Wolfgang\Util\UUID;
use Wolfgang\Interfaces\Model\IBridgeModel;

/**
 * Abstract class representing database model from which all other models that are not a list
 * inherit from.
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.0.1
 */
abstract class Model extends Component implements IModel, IMarshallable {
	use TMarshallable;

	/**
	 *
	 * @var string|int
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	private $model_type;

	/**
	 *
	 * @var string
	 */
	private static $list_class;

	/**
	 * A type 5 UUID representing the id that would be used to insert a new record for this model if
	 * it were to be saved. If this model is one that has been instantiated from an existing
	 * database record then this property will be null
	 *
	 * @var string
	 */
	private $candidate_id;

	/**
	 * The name of the data source name to use in reading, writing and performing database
	 * operations on this model. This property essentially descibes where data for instances of this
	 * model is stored. By default it is null which indicates that the data for the model is stored,
	 * or should be stored in the default database source /data source.
	 *
	 * @var string
	 */
	protected $dsn_name;

	/**
	 *
	 * @var array
	 */
	public static $framework_class_names = [ 
			'FlightAwareApiCall',
			'RecordHistory',
			'Skin',
			'SkinDomain',
			'Timezone'
	];

	/**
	 * An array of key value pairs which represent the state of the record/object when it was last
	 * read from the data source
	 *
	 * @var array
	 */
	private $read_state;

	public function __construct ( ) {
		parent::__construct();
		$this->postInit();
	}

	/**
	 *
	 * @access protected
	 * @return IModel
	 */
	protected function init ( ): IModel {
		parent::init();

		$model_reflection = $this->getReflection();

		foreach($this->getTable()->getColumns() as $column) {
			$column_name = $column->getName();

			if(isset($this->{$column_name})){
				continue;
			}
			
			try {
				$reflection_property = $model_reflection->getProperty( $column_name );
			} catch ( ReflectionException $e ) {
				throw new ModelException( "Property '{$column_name}' of class '{$this->getModelType()}' does not exist. Please implement it." );
			}

			if ( $reflection_property->isPublic() || $reflection_property->isProtected() ) {
				$type = $reflection_property->getType();

				if( $type ){
					switch($type->getName()){
						case 'array':
							$this->{$column_name} = [];
						break;

						case 'bool':
							$this->{$column_name} = false;
						break;

						case 'float':
							$this->{$column_name} = 0.0;
						break;

						case 'int':
							$this->{$column_name} = 0;
						break;

						case 'null':
							$this->{$column_name} = null;
						break;

						case 'object':
							$this->{$column_name} = new \stdClass;
						break;

						case 'string':
							$this->{$column_name} = '';
						break;

						default:
							$this->{$column_name} = null;
						break;
					}
				} else {
					$this->{$column_name} = null;
				}
			}
		}

		return $this;
	}

	/**
	 *
	 * @return \Wolfgang\Model\Model
	 */
	protected function postInit ( ) {
		return $this;
	}

	/**
	 *
	 * @access protected
	 * @return \Wolfgang\Interfaces\Model\IModel
	 */
	protected function preInitialSave ( ): IModel {
		return $this;
	}

	/**
	 *
	 * @access protected
	 * @return \Wolfgang\Interfaces\Model\IModel
	 */
	protected function postInitialSave ( ): IModel {
		return $this;
	}

	/**
	 *
	 * @access protected
	 * @return \Wolfgang\Interfaces\Model\IModel
	 */
	protected function preSave ( ): IModel {
		return $this;
	}

	/**
	 *
	 * @access protected
	 * @return IModel
	 */
	protected function postSave ( ): IModel {
		return $this;
	}

	/**
	 * Called after data from the database has been written to this model
	 *
	 * @return IModel
	 */
	protected function postSourceDataWrite ( ): IModel {
		return $this;
	}

	/**
	 * Called after the instance has been put into the model manager
	 * 
	 * @return IModel
	 */
	public function postModelManagerPut (): IModel {
		return $this;
	}

	/**
	 *
	 * @param string $method
	 * @param array $arguments
	 * @return mixed|NULL
	 */
	public function __call ( $method, $arguments ) {
		$matches = [ ];

		if ( preg_match( "/(get|set)([A-Za-z0-9]+)$/", $method, $matches ) ) {

			$column = preg_replace( "/^(get|set)/", "", $method );
			$column = preg_replace( "/(?<=[a-z])([A-Z])/", "_$1", $column );
			$column = strtolower( $column );

			if ( property_exists( $this, $column ) ) {
				if ( $matches[ 1 ] == 'get' ) {
					return $this->$column;
				} else {
					$this->$column = $arguments[ 0 ];
					return;
				}
			}
		} else if ( preg_match( "/^(sourceDataWrite|postSourceDataWrite)$/", $method ) ) {

			$backtrace = debug_backtrace( 0 | DEBUG_BACKTRACE_IGNORE_ARGS, 3 );

			foreach ( $backtrace as $trace ) {
				if ( $trace[ 'function' ] == 'read' && $trace[ 'class' ] == 'Wolfgang\ORM\Schema' ) {
					// Schema is trying to call method 'postSourceDataWrite', allow it
					//@formatter:off
					return call_user_func_array( [ $this, $method ], $arguments);
					// @formatter:on
				}
			}
		}

		return parent::__call( $method, $arguments );
	}

	/**
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	public static function __callStatic ( $method, $arguments ) {
		if ( preg_match( "/^(findBy)/", $method ) ) {
			if ( empty( $arguments ) ) {
				throw new ModelException( "No argument value provided to find by" );
			} else if ( ! is_string( $arguments[ 0 ] ) && ! is_numeric( $arguments[ 0 ] ) && ! is_object( $arguments[ 0 ] ) ) {
				throw new InvalidArgumentException( "Find by value must be a string, integer or an object that can be represented as a string through its magic method '__toString() '" );
			}

			if ( $method == 'findByCriteria' ) {
				throw new IllegalOperationException( "Calling static public method 'findByCriteria' no longer allowed. Please use corresponding list class for this model in order to find by criteria." );
			}

			$class = get_called_class();

			if ( $method == 'findById' ) {
				return self::getCalledClassSchema()->read( $class, $arguments[ 0 ] );
			}

			$property = str_replace( "findBy", "", $method );
			$property = preg_replace( "/(?<=[a-z])([A-Z])/", "_$1$2", $property );
			$property = strtolower( $property );

			if ( ! property_exists( $class, $property ) ) {
				throw new ModelException( "Property '{$property}' of class '{$class}' does not exist" );
			}

			$class_instance = new $class();
			$list_class = $class_instance->getListClass();
			$list_class_instance = new $list_class();

			$list_class_instance->where( [ 
					$property => $arguments[ 0 ]
			] );

			return $list_class_instance->offsetGet( 0 );
		}

		return parent::__callStatic( $method, $arguments );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Model\IModel::getModelType()
	 */
	public function getModelType ( ): string {
		if ( $this->model_type === null ) {
			$this->model_type = get_class( $this );
		}

		return $this->model_type;
	}

	/**
	 *
	 * @return string|NULL
	 */
	public function getCandidateId ( ): ?string {
		if ( $this->id ) {
			return null;
		}

		if ( ! $this->candidate_id ) {
			$this->candidate_id = UUID::id();
		}

		return $this->candidate_id;
	}

	/**
	 *
	 * @throws ModelException
	 * @return string
	 */
	private function getListClass ( ): string {
		$class = get_class( $this );

		if ( empty( self::$list_class[ $class ] ) ) {
			$list_class = $class . 'List';

			if ( ! class_exists( $list_class ) ) {
				throw new ModelException( "List class {$list_class} for corresponding class '{$class}' does not exist. Implement it to prevent this error." );
			}

			self::$list_class[ $class ] = $list_class;
		}

		return self::$list_class[ $class ];
	}

	/**
	 *
	 * @param array $data
	 * @param array $exceptions
	 * @return \Wolfgang\Model\Model
	 */
	public function applyFieldValues ( array $data, array $exceptions = array()) {
		$table = $this->getTable();

		foreach ( $data as $name => &$datum ) {
			//@formatter:off
			if ( in_array($name, ['id']) && !in_array($name, $exceptions)) {
				throw new ModelException("Cannot update field '{$name}'");
			}
			// @formatter:on

			$column = $table->getColumn( $name );

			if ( ! $column ) {
				continue;
			} else if ( in_array( $name, $exceptions ) ) {
				unset( $data[ $name ] );
				continue;
			}

			if ( $column->isCharType() ) {
				if ( is_array( $datum ) ) {
					$datum = serialize( $datum );
				}

				if ( $column->getCharacterMaxLimit() !== null && (strlen( $datum ) > $column->getCharacterMaxLimit()) ) {
					throw new InvalidArgumentException( "Maximum number of characters exceeded for field '{$name}'" );
				}
			} else if ( $column->isIntegerType() ) {
				$datum = ( int ) $datum;
			} else if ( $column->isDateTimeType() || $column->isDateType() ) {
				if ( ! strtotime( $datum ) ) {
					throw new ModelException( "Value provided for field '{$name}' is not a valid date/datetime" );
				}
			}
		}

		$data = array_filter( $data );

		return $this->sourceDataWrite( $data );
	}

	/**
	 * Writes data from the data source to this instance
	 * 
	 * @param array $data The data to write to this instance
	 * @return IModel A reference to this instance
	 */
	protected function sourceDataWrite ( array $data ): IModel {
		$table = $this->getTable();
		$model_reflection = $this->getReflection();

		foreach ( $data as $column_name => $value ) {
			
			$column_name = strtolower( $column_name );

			$column = $table->getColumn( $column_name );

			if ( ! $column ) {
				continue;
			}

			if ( $column->isIntegerType() ) {
				if ( $value === true || $value === 1 ) {
					$value = 1;
				} else if ( $value === false || $value === 0 || $value === '0' ) {
					$value = 0;
				} else {
					$value = ( int ) $value;
				}
			} else if ( $column->isDateTimeType() || $column->isDateType() ) {
				if ( $value ) {
					$value = new DateTime( $value );
				}
			}

			if ( isset( $value ) ) {
				// Determine if property is public before attempting to access
				try {
					$reflection_property = $model_reflection->getProperty( $column_name );
				} catch ( ReflectionException $e ) {
					throw new ModelException( "Property '{$column_name}' of class '{$this->getModelType()}' does not exist. Please implement it." );
				}

				if ( $reflection_property->isPublic() || $reflection_property->isProtected() ) {
					$type = $reflection_property->getType();

					if( $type ){
						if($type->getName() == 'array'){
							if($value){
								$uvalue = unserialize($value);

								if(!is_array($uvalue)){
									throw new ModelException("Could not unserialize value '{$value}' to assign to object");
								}

								$this->{$column_name} = $uvalue;
							} else {
								$this->{$column_name} = [];
							}
						} else {
							$this->{$column_name} = $value;
						}
					} else {
						$this->{$column_name} = $value;
					}
				} else {
					// Attempt to determine and call getter method for property
					$setter_method = Inflector::setMethodify( $column_name );

					if ( ! $setter_method || ! $model_reflection->hasMethod( $setter_method ) ) {
						throw new ModelException( "Property '{$column_name}' of class '{$this->getModelType()}' is inaccessible in use for source data write." );
					}

					// @formatter:off	
					call_user_func_array( array (	$this, $setter_method ), array($value) );
					// @formatter:on
				}
			}
		}

		$this->postSourceDataWrite();

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Model\IModel::save()
	 */
	public function save ( ): IModel {
		if ( (! $this->getId()) || ($this instanceof IBridgeModel) ) {

			$this->preInitialSave()->preSave();

			$schema = $this->getTable()->getSchema();
			$schema->save( $this );

			return $this->postSave()->postInitialSave();
		} else {
			return $this->update();
		}
	}

	/**
	 *
	 * @access private
	 * @return \Wolfgang\Interfaces\Model\IModel
	 */
	private function update ( ): IModel {
		$this->preSave();

		$this->saveAsHistory();

		$this->getTable()->getSchema()->update( $this );

		$this->postSave();

		return $this;
	}

	/**
	 * Does a hard deleted on the database record this instance represents
	 *
	 * @return Model A reference to this instance
	 */
	public function delete ( ): IModel {
		$this->getTable()->getSchema()->delete( $this );
		return $this;
	}

	/**
	 * @return IModel
	 */
	public function purge():IModel{
		$this->getTable()->getSchema()->delete( $this );
		return $this;
	}

	/**
	 *
	 * @return IModel
	 */
	private function saveAsHistory ( ) {
		//if ( ! Application::getInstance()->isJournaling() ) {
		//	return $this;
		//}

		if ( get_class( $this ) != 'Wolfgang\Model\RecordHistory' ) {
			$record_history = new RecordHistory( $this );
			$record_history->save();
		}

		return $this;
	}

	/**
	 *
	 * @return IDatabaseSchema
	 */
	private function getSchema ( ): IDatabaseSchema {
		if ( $this->getDSNName() ) {
			return SchemaManager::getInstance()->get( $this->getDSNName() );
		} else {
			return SchemaManager::getInstance()->getDefaultSchema( );
		}
	}

	/**
	 *
	 * @access public
	 * @return ITable
	 */
	public function getTable ( ): ITable {
		return $this->getSchema()->getTable( Inflector::tablify( $this->getRootBaseClass()->getName() ) );
	}

	/**
	 *
	 * @return \ReflectionClass
	 */
	public function getRootBaseClass ( ): \ReflectionClass {
		$reflector = new \ReflectionClass( get_class( $this ) );
		$base_class = null;
		do {

			if ( ! empty( $base_class ) ) {
				$reflector = $base_class;
			}

			$base_class = $reflector->getParentClass();
			$base_classname = $base_class->getShortName();
		} while ( $base_classname != 'Model' );

		return $reflector;
	}

	/**
	 * @throws IllegalArgumentException
	 * @param string $dsn_name
	 * @return IModel
	 */
	protected function setDSNName ( string $dsn_name ): IModel {
		if(!$dsn_name){
			throw new IllegalArgumentException("DSN name not provided");
		}

		$this->dsn_name = $dsn_name;
		return $this;
	}

	/**
	 *
	 * @return string|NULL
	 */
	public function getDSNName ( ): ?string {
		return $this->dsn_name;
	}

	/**
	 *
	 * @return IDatabaseSchema
	 */
	private static function getCalledClassSchema ( ): IDatabaseSchema {
		$called_class = get_called_class();
		$called_class_instance = new $called_class();

		return $called_class_instance->getSchema();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IMarshallable::marshall()
	 */
	public function marshall ( ): array {
		$data = [];
		$columns = $this->getTable()->getColumns();
		$exempted_fields = $this->getMarshallFieldExemptions();

		foreach($columns as $column){
			$property = $column->getName();

			if(isset($exempted_fields[$property])){
				continue;
			}

			$data[$property] = $this->{$property};
		}

		return $data;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Model\IModel::getId()
	 */
	public function getId ( ) {
		return $this->id;
	}

	/**
	 * Retrieves a string representation of this object
	 *
	 * @return string $id
	 */
	public function __toString ( ): string {
		return '[id:' . $this->getId() . ']|' . $this->getRootBaseClass()->getName();
	}
}
