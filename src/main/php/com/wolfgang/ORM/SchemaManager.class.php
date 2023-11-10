<?php

namespace Wolfgang\ORM;

use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;
use Wolfgang\Component;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Config\Database as DatabaseConfig;
use Wolfgang\Cache\Cacher;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Network\Uri\Dsn;
use Wolfgang\Exceptions\IllegalStateException;

/**
 *
 * @package Wolfgang\ORM
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
class SchemaManager extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var array<IDatabaseSchema>
	 */
	private $schemas = [ ];
	
	/**
	 *
	 * @var string
	 */
	private $schema_cache_key = 'schemas.<schema_name>';
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$database_config = DatabaseConfig::getAll();
		$database_names = [ ];

		foreach ( $database_config[ $database_config[ 'type' ] ] as $key => $schema ) {
			$schema[ 'name' ] = $key;
			$dsn = Dsn::parse( $schema );
			
			if ( in_array( $dsn->getDatabase(), $database_names ) ) {
				throw new IllegalStateException( "Database with name '{$dsn->getDatabase()}' in schema '{$schema['name']}' has already been defined. Database names must be unique across DSNs/Schemas." );
			}
			
			$database_names[] = $dsn->getDatabase();
			
			$this->create( $key, $dsn );
		}
	}
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getDefaultSchema ( ): IDatabaseSchema {
		return $this->get( array_keys( $this->schemas )[ 0 ] );
	}
	
	/**
	 *
	 * @return array
	 */
	public function getSchemas ( ): array {
		return $this->schemas;
	}
	
	/**
	 *
	 * @access private
	 * @param string key
	 * @param IDatabaseSchema $schema
	 */
	private function put ( string $key, IDatabaseSchema $schema ) {
		if ( array_key_exists( $key, $this->schemas ) ) {
			return;
		}
		
		$this->schemas[ $key ] = $schema;
		$cache_key = preg_replace( "/(<schema_name>)/", $key, $this->schema_cache_key );
		
		Cacher::getInstance()->set( $cache_key, $schema );
	}
	
	/**
	 *
	 * @param string $dsn_name
	 * @throws InvalidArgumentException
	 * @return IDatabaseSchema
	 */
	public function get ( string $dsn_name ): IDatabaseSchema {
		if ( ! $dsn_name ) {
			throw new InvalidArgumentException( "Schema DSN name not provided" );
		}
		
		$dsn_name = strtolower( $dsn_name );
		
		if ( ! empty( $this->schemas[ $dsn_name ] ) ) {
			return $this->schemas[ $dsn_name ];
		}
		
		throw new InvalidArgumentException( "Schema with DSN name '{$dsn_name}' does not exist" );
	}
	
	/**
	 *
	 * @param string $database_name
	 * @throws InvalidArgumentException
	 */
	public function getByDatabaseName ( string $database_name ): ?IDatabaseSchema {
		if ( ! $database_name ) {
			throw new InvalidArgumentException( "Database name not provided" );
		}
		
		foreach ( $this->schemas as $schema ) {
			if ( $schema->getDatabase() == $database_name ) {
				return $schema;
			}
		}
		
		return null;
	}
	
	/**
	 *
	 * @param string $schema_name
	 * @param IDsn $dsn
	 * @throws InvalidArgumentException
	 * @return IDatabaseSchema
	 */
	private function create ( string $schema_name, IDsn $dsn ): IDatabaseSchema {
		if ( ! $schema_name ) {
			throw new InvalidArgumentException( "Schema name must be provided" );
		}
		
		$schema = new Schema( $dsn->getDatabase(), $dsn );
		
		$this->put( $schema_name, $schema );
		
		return $schema;
	}
}