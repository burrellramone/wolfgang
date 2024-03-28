<?php

namespace Wolfgang\ORM;

use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Model\Manager as ModelManager;
use Wolfgang\Interfaces\ORM\IColumn;
use Wolfgang\Cache\Cacher;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\ORM\ITableRelationship;
use Wolfgang\Exceptions\ORM\TableNotExistException;
use Wolfgang\Exceptions\SQL\Exception as SQLException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Util\Inflector;
use Wolfgang\ORM\MySQLi\Column as MySQLiColumn;
use Wolfgang\Database\DriverManager;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;
use Wolfgang\SQL\Statement\Statement as SQLStatement;
use Wolfgang\SQL\Statement\DML\SelectStatement;
use Wolfgang\Proxy\ORM\Table as TableProxy;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class Table extends Component implements ITable {

	/**
	 * The name of the data source name for the schema this table belongs to. This is used to
	 * retrieved the schema from the manager without storing the schema itself as a member of this
	 * table
	 *
	 * @var string
	 */
	protected $schema_dsn_name;

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The qualified name for this table
	 *
	 * @var string
	 */
	protected $quanlified_name;

	/**
	 *
	 * @var string|null
	 */
	protected $comment;

	/**
	 *
	 * @var string
	 */
	protected $type;

	/**
	 *
	 * @var string
	 */
	protected $foreign_key_fields_relationships_cache_key;

	/**
	 *
	 * @var string
	 */
	protected $referenced_key_fields_relationships_cache_key;

	/**
	 *
	 * @var \ArrayObject
	 */
	protected $columns;

	/**
	 *
	 * @var array
	 */
	protected $column_names = [ ];

	/**
	 *
	 * @var \ArrayObject
	 */
	protected $foreign_key_fields_relationships;

	/**
	 *
	 * @var \ArrayObject
	 */
	protected $referenced_key_fields_relationships;

	/**
	 *
	 * @var IModel
	 */
	protected $model;

	/**
	 *
	 * @param string $name
	 */
	public function __construct ( string $name, IDatabaseSchema $schema ) {
		$this->setName( $name );
		$this->setQualifiedName( $schema->getDsn()->getDatabase() . '.' . $name );
		$this->setSchemaDsnName( $schema->getDsn()->getName() );

		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		$schema = $this->getSchema();
		$information_schema = $schema->getInformationSchema();

		$schema_name = $schema->getName();
		$connection = DriverManager::getInstance()->getConnection( $schema->getDsn() );

		try {
			$result = $connection->exec( SQLStatement::fromString( "DESC `{$schema_name}`.{$this->getName()}", [ 
					$schema
			] ) );
		} catch ( SQLException $e ) {
			throw new TableNotExistException( "Table '{$this->getName()}' does not exist in schema '{$schema_name}'" );
		}

		// Get table meta-data, comment
		$statement = "SELECT table_comment, LOWER(table_type) as table_type FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$schema_name}' AND TABLE_NAME = '{$this->getName()}'";
		$result = $connection->exec( $statement );
		$record = $result->current();

		if(!empty($record[ 'table_comment' ])){
		    $this->setComment( $record[ 'table_comment' ] );
		}
		
		$this->setType( $record[ 'table_type' ] );

		$this->foreign_key_fields_relationships_cache_key = $this->getName() . '_foreign_key_fields_relationships';
		$this->referenced_key_fields_relationships_cache_key = $this->getSchema()->getName() . '.' . $this->getName() . '_referenced_key_fields_relationships';

		$this->foreign_key_fields_relationships = new \ArrayObject();
		$this->referenced_key_fields_relationships = new \ArrayObject();

		$this->columns = new \ArrayObject( [ ], \ArrayObject::ARRAY_AS_PROPS );

		$select_statement = new SelectStatement( new TableProxy( $schema, $this->getName() ) );
		$select_statement->addSelectColumn( "*" );

		$result = $connection->exec( $select_statement );

		foreach ( $result->getColumns() as $column ) {
			$column_name = $column[ 'name' ];
			$this->column_names[] = $column_name;

			$statement = "SELECT column_default as default_value,
				is_nullable, data_type,
				character_maximum_length as character_max_limit

				FROM information_schema.Columns
				WHERE
					table_schema = '{$schema_name}'
				AND table_name = '{$this->getName()}'
				AND column_name = '{$column_name}' LIMIT 1";

			$result2 = $connection->exec( $statement );
			$record = $result2->current();
			$column = array_merge( $column, $record );

			$this->columns->{$column_name} = new MySQLiColumn( $this, $column );
		}
	}

	/**
	 *
	 * @param string $name
	 */
	private function setName ( string $name ) {
		if ( empty( $name ) ) {
			throw new IllegalArgumentException( "Table name must not be empty" );
		}

		$this->name = $name;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}

	/**
	 *
	 * @param string $qualified_name
	 */
	private function setQualifiedName ( string $qualified_name ) {
		$this->quanlified_name = $qualified_name;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getQualifiedName()
	 */
	public function getQualifiedName ( ): string {
		return $this->quanlified_name;
	}

	/**
	 *
	 * @param string $schema_dsn_name
	 */
	private function setSchemaDsnName ( string $schema_dsn_name ) {
		$this->schema_dsn_name = $schema_dsn_name;
	}

	/**
	 *
	 * @return string
	 */
	public function getSchemaDsnName ( ): string {
		return $this->schema_dsn_name;
	}

	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getSchema ( ): IDatabaseSchema {
		return SchemaManager::getInstance()->get( $this->getSchemaDsnName() );
	}

	/**
	 *
	 * @return \ArrayObject
	 */
	public function getColumns ( ): \ArrayObject {
		return $this->columns;
	}

	/**
	 * Gets the column names for this table
	 *
	 * @return array
	 */
	public function getColumnNames():array {
		return $this->column_names;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\ITable::getColumn()
	 */
	public function getColumn ( string $column_name ): ?IColumn {
		if ( ! $column_name ) {
			throw new IllegalArgumentException( "Column name must be provided" );
		} else if ( ! in_array( $column_name, $this->column_names ) ) {
			return null;
		}

		foreach ( $this->getColumns() as $column ) {
			if ( $column->getName() == $column_name ) {
				return $column;
			}
		}

		return null;
	}

	/**
	 *
	 * @param string $column_name
	 */
	public function isColumn ( string $column_name ): bool {
		if ( $column_name === null ) {
			throw new IllegalArgumentException( "Column name must be provided" );
		}

		return in_array( $column_name, $this->column_names );
	}

	/**
	 *
	 * @return string
	 */
	private function getForeignKeyFieldsRelationshipsCacheKey ( ) {
		return $this->foreign_key_fields_relationships_cache_key;
	}

	/**
	 *
	 * @return string
	 */
	private function getReferencedKeyFieldsRelationshipsCacheKey ( ) {
		return $this->referenced_key_fields_relationships_cache_key;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getRelationship()
	 */
	public function getRelationship ( ITable $table ): ?ITableRelationship {
		$relationship = $this->getForeignKeyFieldRelationship( $table );

		if ( ! $relationship ) {
			$relationship = $this->getReferencedKeyFieldRelationship( $table );
		}

		return $relationship;
	}

	/**
	 *
	 * @param ITable $table
	 * @return \ArrayObject|null
	 */
	public function getForeignKeyFieldRelationship ( ITable $table ) {
		foreach ( $this->getForeignKeyFieldsRelationships() as $relationhip ) {
			if ( $relationhip->getReferencedTableName() == $table->getName() ) {
				return $relationhip;
			}
		}

		return null;
	}

	/**
	 *
	 * @param ITable $table
	 * @return \ArrayObject|null
	 */
	public function getReferencedKeyFieldRelationship ( $table ) {
		foreach ( $this->getReferencedKeyFieldsRelationships() as $relationhip ) {
			if ( $relationhip->getTableName() == $table->getName() ) {
				return $relationhip;
			}
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getReferencedKeyFieldsRelationships()
	 */
	public function getReferencedKeyFieldsRelationships ( ): \ArrayObject {
		if ( $this->referenced_key_fields_relationships->count() ) {
			return $this->referenced_key_fields_relationships;
		}

		$referenced_key_fields_relationships = Cacher::getInstance()->get( $this->getReferencedKeyFieldsRelationshipsCacheKey() );

		if ( $referenced_key_fields_relationships ) {
			$this->referenced_key_fields_relationships = $referenced_key_fields_relationships;
			return $this->referenced_key_fields_relationships;
		}

		$query = "SELECT
					TABLE_SCHEMA as table_schema_name,
					TABLE_NAME as table_name,
					COLUMN_NAME as column_name,
					CONSTRAINT_NAME as constraint_name,

					REFERENCED_TABLE_SCHEMA as referenced_table_schema_name,
					REFERENCED_TABLE_NAME as referenced_table_name,
					REFERENCED_COLUMN_NAME as referenced_column_name
					FROM
					INFORMATION_SCHEMA.KEY_COLUMN_USAGE
					WHERE
					REFERENCED_TABLE_NAME = '{$this->getName()}'
					AND
					REFERENCED_TABLE_NAME IS NOT NULL
					AND TABLE_SCHEMA = 'airportruns'";

		$connection = $this->getSchema()->getConnection();
		$result = $connection->exec( $query );

		foreach ( $result->fetchAll() as $relationship ) {
			$this->referenced_key_fields_relationships->append( new ReferencedKeyFieldRelationship( $relationship ) );
		}

		$this->cacheReferencedKeyFieldsRelationships();

		return $this->referenced_key_fields_relationships;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getForeignKeyFieldsRelationships()
	 */
	public function getForeignKeyFieldsRelationships ( ): \ArrayObject {
		if ( $this->foreign_key_fields_relationships->count() ) {
			return $this->foreign_key_fields_relationships;
		}

		$foreign_key_fields_relationships = Cacher::getInstance()->get( $this->getForeignKeyFieldsRelationshipsCacheKey() );

		if ( $foreign_key_fields_relationships ) {
			$this->foreign_key_fields_relationships = $foreign_key_fields_relationships;
			return $this->foreign_key_fields_relationships;
		}

		$query = "SELECT
				KEY_COLUMN_USAGE.CONSTRAINT_NAME as constraint_name,

				KEY_COLUMN_USAGE.TABLE_SCHEMA as table_schema_name,
				KEY_COLUMN_USAGE.TABLE_NAME as table_name,
				KEY_COLUMN_USAGE.COLUMN_NAME as column_name,
				
				REFERENTIAL_CONSTRAINTS.DELETE_RULE as delete_rule,
				REFERENTIAL_CONSTRAINTS.UPDATE_RULE as update_rule,

				KEY_COLUMN_USAGE.CONSTRAINT_NAME as constraint_name,

				KEY_COLUMN_USAGE.REFERENCED_TABLE_SCHEMA as referenced_table_schema_name,
				KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME as referenced_table_name,
				KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME as referenced_column_name

				FROM
					INFORMATION_SCHEMA.KEY_COLUMN_USAGE
					JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS ON REFERENTIAL_CONSTRAINTS.CONSTRAINT_SCHEMA = '{$this->getSchema()->getName()}' 
						AND REFERENTIAL_CONSTRAINTS.TABLE_NAME = '{$this->getName()}'
						AND REFERENTIAL_CONSTRAINTS.CONSTRAINT_NAME = KEY_COLUMN_USAGE.CONSTRAINT_NAME
				WHERE
					KEY_COLUMN_USAGE.TABLE_NAME = '{$this->getName()}'
					AND
						KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME IS NOT NULL
					AND 
						KEY_COLUMN_USAGE.TABLE_SCHEMA = '{$this->getSchema()->getName()}'";

		$connection = $this->getSchema()->getConnection();
		$result = $connection->exec( $query );

		foreach ( $result->fetchAll() as $relationship ) {
			$this->foreign_key_fields_relationships->append( new ForeignKeyFieldRelationship( $relationship ) );
		}

		$this->cacheForeignKeyFieldsRelationships();

		return $this->foreign_key_fields_relationships;
	}

	/**
	 *
	 * @access private
	 * @return null
	 */
	private function cacheReferencedKeyFieldsRelationships ( ) {
		Cacher::getInstance()->set( $this->getReferencedKeyFieldsRelationshipsCacheKey(), $this->referenced_key_fields_relationships );
	}

	/**
	 *
	 * @access private
	 * @return null
	 */
	private function cacheForeignKeyFieldsRelationships ( ) {
		Cacher::getInstance()->set( $this->getForeignKeyFieldsRelationshipsCacheKey(), $this->foreign_key_fields_relationships );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\ITable::getModel()
	 */
	public function getModel ( ): IModel {
		if ( ! $this->model ) {
			$this->model = ModelManager::getInstance()->create( Inflector::classify( $this->getName() ) );
		}
		return $this->model;
	}

	/**
	 *
	 * @param string $comment
	 */
	private function setComment ( string $comment ) {
		$this->comment = $comment;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getComment()
	 */
	public function getComment ( ): ?string {
		return $this->comment;
	}

	/**
	 *
	 * @param string $type
	 */
	private function setType ( string $type ) {
		$this->type = $type;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getType()
	 */
	public function getType ( ): string {
		return $this->type;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		return $this->getName();
	}
}
