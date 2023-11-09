<?php

namespace Wolfgang\Database;

use ArrayObject;
use Wolfgang\Config\Database as DatabaseConfig;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Database\Component as DatabaseComponent;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Interfaces\Database\IConnection as IDatabaseConnection;
use Wolfgang\Database\MySQL\Connection as MySQLConnection;
use Wolfgang\Exceptions\InvalidStateException;

/**
 *
 * @package Wolfgang\Database
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class DriverManager extends DatabaseComponent implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var \ArrayObject
	 */
	private $connections;
	
	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->connections = new \ArrayObject( [ ], \ArrayObject::ARRAY_AS_PROPS );
	}
	
	/**
	 * Gets a specific connection to a database by a provided data source name
	 *
	 * @param IDsn $dsn The database source name to use in getting the connection
	 * @return IDatabaseConnection The database connection retrieved by the provided data source
	 *         name
	 */
	public function getConnection ( IDsn $dsn ): IDatabaseConnection {
		$connection_key = md5( ( string ) $dsn );
		
		if ( empty( $this->connections[ $connection_key ] ) ) {
			$database_type = DatabaseConfig::get( 'type' );
			
			switch ( $database_type ) {
				case 'db2' :
					break;
				
				case 'mysql' :
					$this->connections->{$connection_key} = new MySQLConnection( $dsn );
					break;
				
				case 'postgresql' :
					break;
				
				default :
					new InvalidStateException( "Invalid, unknown and/or unsupported database type '{$database_type}'" );
					break;
			}
		}
		
		return $this->connections->{$connection_key};
	}
	
	/**
	 * Returns a list of connections that have at least been previously opened
	 *
	 * @return ArrayObject The list of connections
	 */
	public function getConnections ( ): ArrayObject {
		return $this->connections;
	}
	
	/**
	 * Indicates to all connections to listen for the execution of a statment to determine when to
	 * begin a transaction
	 */
	public function begin ( ) {
		foreach ( $this->getConnections() as $connection ) {
			$connection->begin();
		}
	}
	
	/**
	 * Indicates to all connections to commit any open transactions
	 */
	public function commit ( ): void {
		foreach ( $this->getConnections() as $connection ) {
			$connection->commit();
		}
	}
	
	/**
	 * Indicates to all connections to rollback any open transactions
	 */
	public function rollback ( ): void {
		foreach ( $this->getConnections() as $connection ) {
			$connection->rollback();
		}
	}
}
