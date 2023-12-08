<?php

namespace Wolfgang\Database;

use Wolfgang\Interfaces\Database\IConnection;
use Wolfgang\Database\Component as DatabaseComponent;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\Network\IDsn;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Connection extends DatabaseComponent implements IConnection {
	
	/**
	 *
	 * @var IDsn
	 */
	protected $dsn;
	
	/**
	 *
	 * @var resource
	 */
	protected $connection;
	
	/**
	 *
	 * @var string
	 */
	protected $database;
	
	/**
	 *
	 * @var string
	 */
	protected $encryption_key;
	
	/**
	 *
	 * @var array
	 */
	protected static $macros = [ 
			'encryption_key' => '$<DB_ENCRYPT_DECRYPT_KEY>' 
	];
	
	/**
	 *
	 * @param IDsn $dsn
	 */
	public function __construct ( IDsn $dsn ) {
		parent::__construct();
		
		$this->setDsn( $dsn );
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
	 * @param string $key
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public static function getMacro ( $key ): string {
		if ( ! $key ) {
			throw new IllegalArgumentException( "Key must be provided" );
		} else if ( ! array_key_exists( $key, self::$macros ) ) {
			throw new InvalidArgumentException( "Macro does not exist for key '{$key}'" );
		}
		
		return self::$macros[ $key ];
	}
	
	/**
	 *
	 * @access protected
	 * @param string $statement
	 * @throws IllegalArgumentException
	 * @return string
	 */
	protected function replaceSQLMacros ( string $statement ): string {
		if ( ! $statement ) {
			throw new IllegalArgumentException( "Query not provided" );
		}
		// Encryption Key Macros
		return str_replace( self::getMacro( 'encryption_key' ), $this->getDsn()->getEncryptionKey(), $statement );
	}
}
