<?php

namespace Wolfgang\ORM;

use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Cache\Cacher;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;
use Wolfgang\Component;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class TableManager extends Component {
	
	/**
	 *
	 * @var TableManager
	 */
	private static $instance;
	
	/**
	 *
	 * @var array
	 */
	private $tables = [ ];
	
	/**
	 *
	 * @var IDatabaseSchema
	 */
	private $schema;
	
	/**
	 *
	 * @var string
	 */
	private $tables_cache_key = 'tables.<dsn_name>.<schema_name>.<table_name>';
	
	/**
	 *
	 * @param IDatabaseSchema $schema
	 */
	public function __construct ( IDatabaseSchema $schema ) {
		parent::__construct();
		
		$this->schema = $schema;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @return array
	 */
	public function getTables ( ): array {
		return $this->tables;
	}
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getSchema ( ): IDatabaseSchema {
		return $this->schema;
	}
	
	/**
	 *
	 * @param string $table_name
	 * @return string
	 */
	private function getTableCacheKey ( string $table_name ): string {
		// $key = str_replace( "<dsn_name>", "", $this->tables_cache_key );
		$key = $this->tables_cache_key;
		$key = str_replace( "<schema_name>", $this->getSchema()->getName(), $key );
		$key = str_replace( "<table_name>", $table_name, $key );
		
		return $key;
	}
	
	/**
	 *
	 * @param ITable $table
	 */
	private function put ( ITable $table ) {
		$this->tables[ $table->getName() ] = $table;
		Cacher::getInstance()->set( $this->getTableCacheKey( $table->getName() ), $table );
	}
	
	/**
	 *
	 * @param string $table_name
	 * @throws InvalidArgumentException
	 * @return ITable
	 */
	public function get ( string $table_name ): ITable {
		if ( ! is_string( $table_name ) ) {
			throw new InvalidArgumentException( "Table name must be a string" );
		} else if ( ! empty( $this->tables[ $table_name ] ) ) {
			return $this->tables[ $table_name ];
		}
		
		$table = Cacher::getInstance()->get( $this->getTableCacheKey( $table_name ) );
		
		if ( $table ) {
			$this->tables[ $table_name ] = $table;
			return $table;
		}
		
		return $this->create( $table_name );
	}
	
	/**
	 *
	 * @param string $table_name
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 * @return ITable
	 */
	private function create ( string $table_name ): ITable {
		if ( ! $table_name ) {
			throw new IllegalArgumentException( "Table name must be provided" );
		} else if ( ! is_string( $table_name ) ) {
			throw new InvalidArgumentException( "Table name must be a string" );
		}
		
		$table = new Table( $table_name, $this->getSchema() );
		
		$this->put( $table );
		
		return $table;
	}
}