<?php

namespace Wolfgang\ORM;

// PHP
use ReflectionException;
// Wolfgang
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Model\IEncrypted;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;
use Wolfgang\Interfaces\SQL\IUnduplicable;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Model\Manager as ModelManager;
use Wolfgang\SQL\Statement\DML\DeleteStatement;
use Wolfgang\SQL\Statement\DML\InsertStatement;
use Wolfgang\SQL\Statement\DML\UpdateStatement;
use Wolfgang\Cache\Cacher;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Interfaces\Database\IConnection as IDatabaseConnection;
use Wolfgang\Database\DriverManager;
use Wolfgang\SQL\Statement\DML\SelectStatement;
use Wolfgang\Interfaces\IImmutable;
use Wolfgang\Util\UUID;
use Wolfgang\Util\ModelDelta;
use Wolfgang\Proxy\ORM\Table as TableProxy;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Exceptions\ORM\TableNotExistException;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Interfaces\Model\IBridgeModel;
use Wolfgang\Util\Inflector;
use Wolfgang\Exceptions\ORM\Exception as ORMException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Schema extends Component implements IDatabaseSchema {

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @var TableManager
	 */
	private $table_manager;

	/**
	 *
	 * @var IDsn
	 */
	private $dsn;

	/**
	 *
	 * @var string
	 */
	private $join_scheme_cache_key = 'join_scheme.f5785c35-2cc7-40bf-9439-80eb86adfcc5';

	/**
	 * A template for the key to be used in caching the names of the tables that are a part of this
	 * schema. Template placeholders will be replaced with values in respects of this schema on
	 * initialization of this instance
	 *
	 * @var string
	 */
	private $table_names_cache_key = "<schema_name>.table_names";

	/**
	 *
	 * @var array
	 */
	private $join_scheme;

	/**
	 *
	 * @var array
	 */
	private $table_names = [ ];

	/**
	 *
	 * @var \ArrayObject
	 */
	private $tables;

	/**
	 *
	 * @param string $name
	 * @param IDsn $dsn
	 * @throws InvalidArgumentException
	 */
	public function __construct ( string $name, IDsn $dsn ) {
		if ( empty( $name ) ) {
			throw new InvalidArgumentException( "Schema name not provided" );
		}

		$this->setName( $name );
		$this->setDsn( $dsn );

		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->table_names_cache_key = str_replace( "<schema_name>", $this->getName(), $this->table_names_cache_key );
		$this->table_manager = new TableManager( $this );
		$this->tables = new \ArrayObject();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ISchema::getTable()
	 */
	public function getTable ( string $table_name ) {
		if ( ! $this->tableExists( $table_name ) ) {
			throw new TableNotExistException( "The table '{$table_name}' does not exist within the schema '{$this->getName()}'." );
		}

		return $this->getTableManager()->get( $table_name );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ISchema::getTables()
	 */
	public function getTables ( ): \ArrayObject {
		if ( $this->tables->count() ) {
			return $this->tables;
		}

		foreach ( $this->getTableNames() as $table_name ) {
			$this->tables->append( $this->getTable( $table_name ) );
		}

		return $this->tables;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ISchema::getInformationSchema()
	 */
	public function getInformationSchema ( ): IDatabaseSchema {
		return SchemaManager::getInstance()->get( $this->getDsn()->getInformationSchema() );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ISchema::tableExists()
	 */
	public function tableExists ( string $table_name ): bool {
		return in_array( $table_name, $this->getTableNames() );
	}

	/**
	 *
	 * @param string $name
	 */
	private function setName ( $name ) {
		if ( ! $name ) {
			throw new IllegalArgumentException( 'Schema name must be provided' );
		}

		$this->name = $name;
	}

	/**
	 * Gets a list of the names of the tables that exists within this schema. If the list is empty
	 * it will attempt to load it from the information schema associated with schema
	 *
	 * @return array
	 */
	private function getTableNames ( ): array {
		if ( ! empty( $this->table_names ) ) {
			return $this->table_names;
		}

		$this->table_names = Cacher::getInstance()->get( $this->table_names_cache_key );

		if ( ! empty( $this->table_names ) ) {
			return $this->table_names;
		}

		$statement = "SELECT TABLE_NAME 
					  FROM information_schema.TABLES 
					  WHERE TABLE_SCHEMA = '{$this->getName()}';";

		$result = $this->getConnection()->exec( $statement );

		foreach ( $result as $row ) {
			$this->table_names[] = $row[ 'TABLE_NAME' ];
		}

		Cacher::getInstance()->set( $this->table_names_cache_key, $this->table_names );

		return $this->table_names;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ISchema::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}

	/**
	 *
	 * @return TableManager
	 */
	public function getTableManager ( ): TableManager {
		return $this->table_manager;
	}

	/**
	 *
	 * @param IDsn $dsn
	 */
	private function setDsn ( IDsn $dsn ) {
		$this->dsn = $dsn;
	}

	/**
	 *
	 * @return IDsn
	 */
	public function getDsn ( ): IDsn {
		return $this->dsn;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ISchema::getDatabase()
	 */
	public function getDatabase ( ): string {
		return $this->getDsn()->getDatabase();
	}

	/**
	 * Gets a database connection by the data source name described by this schema
	 *
	 * @return IDatabaseConnection The database connection to this schema
	 */
	public function getConnection ( ): IDatabaseConnection {
		return DriverManager::getInstance()->getConnection( $this->getDsn() );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ISchema::save()
	 */
	public function save ( IModel $model ): void {
		if ( $model->getId() && ! ($model instanceof IBridgeModel) ) {
			throw new InvalidArgumentException( "Model record must not already exist." );
		}

		$connection = $this->getConnection();

		$table = $model->getTable();
		$encrypted = ($model instanceof IEncrypted);
		$statement = new InsertStatement( $table );
		$model_reflection = $model->getReflection();

		foreach ( $table->getColumns() as $column ) {
			$column_name = $column->getName();

			if ( $column_name == 'id' ) {
				$value = $model->id = UUID::id();
			} else {
				// Determine if property is public before attempting to access
				try {
					$reflection_property = $model_reflection->getProperty( $column_name );
				} catch ( ReflectionException $e ) {
					throw new ORMException( "Property '{$column_name}' of class '{$model->getModelType()}' does not exist. Please implement it." );
				}

				if ( $reflection_property->isPublic() ) {
					$value = $model->{$column_name};
				} else {
					// Attempt to determine and call getter method for property
					$getter_method = Inflector::getMethodify( $column_name );

					if ( ! $getter_method || ! $model_reflection->hasMethod( $getter_method ) ) {
						throw new ORMException( "Property '{$column_name}' of class '{$model->getModelType()}' is inaccessible in use for schema write. Implement getter method for the property." );
					}

					$value = call_user_func( array (
							$model,
							$getter_method
					) );
				}
			}

			if ( $value === null && ! $column->isNullable() ) {
				$value = $column->getDefaultValue();

				if ( $value === null ) {
					throw new InvalidStateException( "Column '{$column_name}' of table '{$table->getName()}' cannot not be null." );
				}
			}

			if ( $encrypted && $model->isEncryptedColumn( $column ) && $column->isEncrypted() ) {
				$statement->bind( $column_name, $value, true );
			} else {
				$statement->bind( $column_name, $value );
			}

			if ( ($model instanceof IUnduplicable) ) {
				$columns = $model->getUpdateColumns();

				if ( empty( $columns ) ) {
					$statement->onDuplicateKeyUpdate( [ 
							'id'
					] );
				} else {
					$statement->onDuplicateKeyUpdate( $columns );
				}
			}
		}

		$connection->exec( $statement );

		// Read the modal from the database
		$model = $this->read( $model->getModelType(), $model->getId() );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ISchema::read()
	 */
	public function read ( string $type, string $id ): ?IModel {
		if ( ! $type ) {
			throw new InvalidArgumentException( "Type must be provided" );
		} else if ( ! $id ) {
			throw new InvalidArgumentException( "Record id must be provided" );
		}

		$connection = $this->getConnection();
		$encryption_key = $connection->getDsn()->getEncryptionKey();
		$model_manager = ModelManager::getInstance();
		$instance = $model_manager->get( $type, $id );

		if ( $instance ) {
			if ( $instance->getId() != $id ) {
				throw new Exception( "Error reading instance of type '{$type}' and id '{$id}' from model manager. Instance does not fit request." );
			}

			return $instance;
		}

		$key = $type . $id;

		$instance = Cacher::getInstance()->get( $key );

		if ( $instance ) {
			// Put it into model manager so we don't have to read from the cache next time around
			$model_manager->put( $instance );
			return $instance;
		}

		$instance = $model_manager->create( $type );
		$table = $instance->getTable();

		$statement = new SelectStatement( $table );
		$encrypted = ($instance instanceof IEncrypted);

		foreach ( $table->getColumns() as $column ) {
			$column_name = $column->getName();

			if ( $encrypted && $instance->isEncryptedColumn( $column ) && $column->isEncrypted() ) {
				$statement->addSelectColumn( "COALESCE(AES_DECRYPT({$column_name}, '{$encryption_key}'), {$column_name})", $column_name );
			} else if ( $column->isGeometryType() ) {
				$statement->addSelectColumn( "ASTEXT({$column_name})", $column_name );
			} else {
				$statement->addSelectColumn( $column_name );
			}
		}

		if ( ($instance instanceof IBridgeModel) ) {
			$id_parts = preg_split( "/[_]/", $id );
			$bridge_column_names = $instance->getBridgeColumnNames();

			if ( ! count( $bridge_column_names ) ) {
				throw new Exception( "Model does not have any bridge columns" );
			} else if ( count( $bridge_column_names ) == 1 ) {
				throw new Exception( "Bridge model must feature more than one bridge column" );
			} else if ( count( $bridge_column_names ) != count( $id_parts ) ) {
				throw new Exception( "The number of bridge column names and id parts do not match" );
			}

			foreach ( $bridge_column_names as $index => $bridge_column_name ) {
				$statement->andWhere( [ 
						$bridge_column_name => $id_parts[ $index ]
				] );
			}

			$statement->limit( 1 );
		} else {
			$statement->where( [ 
					'id' => $id
			] )->limit( 1 );
		}

		$result = $connection->exec( $statement );

		if ( ! $result->count() ) {
			return null;
		}

		$instance->sourceDataWrite( $result->current() );

		$model_manager->put( $instance );

		Cacher::getInstance()->set( $key, $instance );

		return $instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ISchema::update()
	 */
	public function update ( IModel $model ): IModel {
		if ( ! $model->getId() ) {
			throw new IllegalStateException( "Model must already exist to be updated" );
		} else if ( ($model instanceof IImmutable) ) {
			throw new IllegalArgumentException( "Immutable models cannot be updated onced saved." );
		}

		$connection = $this->getConnection();
		$table = $model->getTable();
		$encrypted = ($model instanceof IEncrypted);
		$statement = new UpdateStatement( $table );
		$model_delta = new ModelDelta( $model );

		if ( ! $model_delta->count() ) {
			return $model;
		}

		foreach ( $model_delta as $delta_change ) {
			$column_name = $delta_change->getProperty();
			$column = $table->getColumn( $column_name );
			$value = $delta_change->getNewValue();

			if ( ($value === null) && ! $column->isNullable() ) {
				$value = $column->getDefaultValue();
			} else if ( empty( $value ) && $column->isNullable() ) {
				$value = null;
			}

			if ( $encrypted && $model->isEncryptedColumn( $column ) && $column->isBlobType() ) {
				$statement->bind( $column_name, $value, true );
			} else {
				$statement->bind( $column_name, $value );
			}
		}

		$statement->where( [ 
				'id' => $model->getId()
		] );

		$connection->exec( $statement );

		ModelManager::getInstance()->replace( $model );

		Cacher::getInstance()->delete( $model->getModelType() . $model->getId() );

		return $model;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ISchema::delete()
	 */
	public function delete ( IModel $model ): IModel {
		$connection = $this->getConnection();
		$table = $model->getTable();

		$statement = new DeleteStatement( $table );

		if ( ($model instanceof IBridgeModel) ) {
			$id_parts = preg_split( "/[_]/", $model->getId() );

			foreach ( $model->getBridgeColumnNames() as $index => $bridge_column_name ) {
				$statement->andWhere( [ 
						$bridge_column_name => $id_parts[ $index ]
				] );
			}

			$statement->limit( 1 );
		} else {
			$statement->where( function ( IConditionalExpression $expression ) use ( $model ) {
				return $expression->eq( 'id', $model->getId() );
			} );
		}

		$connection->exec( $statement );
		return $model;
	}

	/**
	 */
	public function buildEntityRelationshipGraph ( ) {
		$graph = new EntityRelationshipGraph( $this );
		$graph->save();
	}

	/**
	 * Gets a list of tables that have a direct relationship with a provided table
	 *
	 * @param ITable $table The table to retrieve the related tables for
	 * @return array The set of tables which are directly related to the provided table. Will be
	 *         empty if the provided table does not have any tables directly related to it
	 */
	public static function getRelatedTables ( ITable $table ): array {
		$related_tables = [ ];
		$ormConnection = SchemaManager::getInstance()->get( 'orm' )->getConnection();

		$statement = "
											SELECT
											DISTINCT
							
											node_relationship.node_id,
											node.schema_name,
											node.name as node_name,
							
											node_relationship.related_node_id,
											related_node.name as related_node_name
							
											FROM orm.node_relationship
							
											JOIN orm.node on node.id = node_id
											JOIN orm.node related_node on related_node.id = node_relationship.related_node_id
							
											LEFT JOIN orm.node_relationship related_node_node_relationship on related_node_node_relationship.node_id = related_node.id
											LEFT JOIN orm.node secondary_related_node on secondary_related_node.id = related_node_node_relationship.related_node_id
							
											WHERE related_node.schema_name = '{$table->getSchema()->getDatabase()}' 
											AND related_node.name = '{$table->getName()}';";

		$result = $ormConnection->exec( $statement );

		foreach ( $result as $record ) {
			$related_tables[] = new TableProxy( SchemaManager::getInstance()->getByDatabaseName( $record[ 'schema_name' ] ), $record[ 'node_name' ] );
		}

		return $related_tables;
	}
}
