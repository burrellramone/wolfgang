<?php

namespace Wolfgang\Interfaces\Database;

use Wolfgang\Interfaces\SQL\Statement\IStatement;

/**
 *
 * @package Wolfgang\Interfaces\Database
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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
}
