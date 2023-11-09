<?php

namespace Wolfgang\Database\DB2;

use Wolfgang\Database\Connection as DatabaseConnection;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Database\DB2\IDB2Connection;
use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Interfaces\Database\IConnection;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0
 */
final class Connection extends DatabaseConnection implements IDB2Connection {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::rollback()
	 */
	public function rollback ( ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getEncryptionKey()
	 */
	public function getEncryptionKey ( ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getDatabase()
	 */
	public function getDatabase ( ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::commit()
	 */
	public function commit ( ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::escape()
	 */
	public function escape ( &$escapeval ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getConnection()
	 */
	public function getConnection ( IDsn $dsn ): IConnection {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::begin()
	 */
	public function begin ( ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::exec()
	 */
	public function exec ( $query ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getErrorNumber()
	 */
	public function getErrorNumber ( ): int {
	}
}