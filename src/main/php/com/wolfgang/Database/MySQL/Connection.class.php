<?php

namespace Wolfgang\Database\MySQL;

use Wolfgang\Exceptions\SQL\Exception as SQLException;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\SQL\StatementManager;
use Wolfgang\Interfaces\Database\MySQL\IMySQLConnection;
use Wolfgang\Database\Connection as DatabaseConnection;
use Wolfgang\Exceptions\Database\UnattainableConnectionException;
use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Exceptions\InvalidStateException;

/**
 *
 * @package Wolfgang\Database\MySQL
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class Connection extends DatabaseConnection implements IMySQLConnection {
	
	/**
	 *
	 * @param IDsn $dsn
	 * @throws UnattainableConnectionException
	 */
	public function __construct ( IDsn $dsn ) {
		if ( $dsn ) {
			$this->connection = @new \mysqli( $dsn->getHost(), $dsn->getUsername(), $dsn->getPassword(), $dsn->getDatabase(), $dsn->getPort() );
			$this->database = $dsn->getDatabase();
			$this->encryption_key = $dsn->getEncryptionKey();
		}
		
		if ( ! empty( $this->connection->connect_error ) ) {
			throw new UnattainableConnectionException( "Unable to connect to '{$dsn->getHost()}'. Error: " . $this->connection->connect_error );
		}
		
		$this->connection->set_charset( $dsn->getCharSet() );
		
		parent::__construct( $dsn );
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
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::exec()
	 */
	public function exec ( $statement ) {
		if ( ($statement instanceof IStatement) ) {
			$statement_manager = StatementManager::getInstance();
			$sql = $statement_manager->get( $statement );
			
			if ( empty( $sql ) ) {
				$sql = $statement_manager->put( $statement );
				
				if ( empty( $sql ) ) {
					throw new InvalidStateException( "Unable to retrieve stored statement" );
				}
			}
		} else {
			$sql = $statement;
		}
		
		$sql = $this->replaceSQLMacros( $sql );
		$result = $this->connection->query( $sql );
		
		if ( $this->connection->error ) {
			echo $sql;
			throw new SQLException( "Error: {$this->connection->errno} {$this->connection->error} Query: {$sql}" );
		}
		
		if ( ($result instanceof \mysqli_result) ) {
			$result = new ResultSet( $result );
		}
		
		return $result;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getErrorNumber()
	 */
	public function getErrorNumber ( ): int {
		return $this->connection->errno;
	}
	
	/**
	 *
	 * @param mixed $val
	 */
	public function escape ( &$escapeval ) {
		if ( is_object( $escapeval ) ) {
			foreach ( get_object_vars( $escapeval ) as $property => $value ) {
				$this->escape( $escapeval->{$property} );
			}
		} else if ( is_array( $escapeval ) ) {
			foreach ( $escapeval as $property => $value ) {
				$this->escape( $escapeval[ $property ] );
			}
		} else {
			$escapeval = $this->connection->real_escape_string( $escapeval );
		}
		
		return $escapeval;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::begin()
	 */
	public function begin ( ) {
		$this->rollback();
		$this->connection->begin_transaction();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::rollback()
	 */
	public function rollback ( ) {
		$this->connection->rollback();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::commit()
	 */
	public function commit ( ) {
		$this->connection->commit();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getDatabase()
	 */
	public function getDatabase ( ) {
		return $this->database;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Database\IConnection::getEncryptionKey()
	 */
	public function getEncryptionKey ( ): ?string {
		return $this->getDsn()->getEncryptionKey();
	}
}
