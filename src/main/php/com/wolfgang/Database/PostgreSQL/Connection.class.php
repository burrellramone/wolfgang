<?php

namespace Wolfgang\Database\PostgreSQL;

use Wolfgang\Interfaces\Database\PostgreSQL\IPostgreSQLConnection;
use Wolfgang\Database\Connection as DatabaseConnection;
use Wolfgang\Interfaces\Database\IConnection;
use Wolfgang\Interfaces\Network\IDsn;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Database\PostgreSQL
 * @since Version 1.0.0
 */
final class Connection extends DatabaseConnection implements IPostgreSQLConnection {
	
	public function rollback ( ) {
	}
	
	public function getEncryptionKey ( ) {
	}
	
	public function getDatabase ( ) {
	}
	
	public function commit ( ) {
	}
	
	public function escape ( &$escapeval ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getConnection()
	 */
	public function getConnection ( IDsn $dsn ): IConnection {
	}
	
	public function begin ( ) {
	}
	
	public function exec ( $query ) {
	}
	
	public function getErrorNumber ( ): int {
	}
}

