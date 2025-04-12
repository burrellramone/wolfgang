<?php

namespace Wolfgang\Interfaces\Database;

use Wolfgang\Interfaces\SQL\Statement\IStatement;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IConnection {
	
	/**
	 *
	 * @var string
	 */
	const KIND_MYSQL = 'mysql';
	
	/**
	 *
	 * @var string
	 */
	const KIND_DB2 = 'db2';
	
	/**
	 *
	 * @var string
	 */
	const KIND_POSTGRESQL = 'postgresql';
	
	/**
	 *
	 * @param IStatement|string $query
	 */
	public function exec ( $query );
	
	/**
	 *
	 * @param mixed $escapeval
	 */
	public function escape ( &$escapeval );
	
	/**
	 */
	public function begin ( );
	
	/**
	 */
	public function rollback ( );
	
	/**
	 */
	public function commit ( );
	
	/**
	 */
	public function getDatabase ( );
	
	/**
	 */
	public function getEncryptionKey ( );
	
	/**
	 *
	 * @return int
	 */
	public function getErrorNumber ( ): int;


	/**
	 * @see https://www.php.net/manual/en/mysqli.insert-id.php
	 * @return int
	 */
	public function getLastInsertId ( ): int;
}
