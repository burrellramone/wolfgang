<?php

namespace Wolfgang\Network\Uri;

use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
final class Dsn extends Uri implements IDsn {
	
	/**
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 *
	 * @var string
	 */
	protected $username;
	
	/**
	 *
	 * @var string
	 */
	protected $password;
	
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
	 * @var string
	 */
	protected $charset;
	
	/**
	 *
	 * @var string
	 */
	protected $information_schema;

	/**
	 * @var bool
	 */
	private $password_encoded = false;
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Network\Uri\Uri::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$user_info = explode( ':', $this->getUserInfo() );

		$this->setUsername( $user_info[ 0 ] );
		$this->setPassword( $user_info[ 1 ] );
		$this->setDatabase( str_replace( "/", "", $this->getPath() ) );
	}
	
	/**
	 *
	 * @param string $name
	 */
	private function setName ( string $name ) {
		$this->name = $name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string {
		return $this->name;
	}
	
	/**
	 * *
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDsn::getDiver()
	 */
	public function getDiver ( ): string {
		return $this->getScheme();
	}
	
	/**
	 *
	 * @param string $username
	 */
	private function setUsername ( string $username ) {
		$this->username = $username;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDsn::getUsername()
	 */
	public function getUsername ( ): string {
		return $this->username;
	}
	
	/**
	 *
	 * @param string $password
	 */
	private function setPassword ( string $password ) {
		$this->password = $password;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDsn::getPassword()
	 */
	public function getPassword ( ): string {
		if($this->password_encoded){
			//We must base64 decode password as it was originally encoded before 
			return base64_decode($this->password);
		}
		
		return $this->password;
	}
	
	/**
	 *
	 * @param string $database
	 */
	private function setDatabase ( string $database ) {
		$this->database = $database;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDsn::getDatabase()
	 */
	public function getDatabase ( ): string {
		return $this->database;
	}
	
	/**
	 *
	 * @param string $encryption_key
	 * @throws InvalidArgumentException
	 */
	protected function setEncryptionKey ( string $encryption_key ) {
		if ( empty( $encryption_key ) ) {
			throw new InvalidArgumentException( "Encryption key must be provided" );
		}
		
		$this->encryption_key = $encryption_key;
	}
	
	/**
	 *
	 * @return string|NULL
	 */
	public function getEncryptionKey ( ): ?string {
		return $this->encryption_key;
	}
	
	/**
	 *
	 * @param string $charset
	 * @throws InvalidArgumentException
	 */
	protected function setCharSet ( string $charset ) {
		if ( empty( $charset ) ) {
			throw new InvalidArgumentException( "Character set must be provided" );
		}
		
		$this->charset = $charset;
	}
	
	/**
	 *
	 * @return string|NULL
	 */
	public function getCharSet ( ): ?string {
		return $this->charset;
	}
	
	/**
	 *
	 * @param string $information_schema
	 */
	private function setInformationSchema ( string $information_schema ) {
		$this->information_schema = $information_schema;
	}
	
	/**
	 * Gets the DSN name for the informations schema for this DSN
	 *
	 * @return string
	 */
	public function getInformationSchema ( ): string {
		return $this->information_schema;
	}
	
	/**
	 * Parses a DSN array to a an instane of Wolfgang\Interfaces\Network\IDsn
	 *
	 * @param array $dsn
	 * @return string
	 */
	public static function parse ( array $dsn ): IDsn {
		$dsn = array_filter($dsn);

		if ( empty( $dsn ) ) {
			throw new InvalidArgumentException( "DSN array not provided" );
		} else if ( empty( $dsn[ 'name' ] ) ) {
			throw new InvalidArgumentException( "DSN name not provided in DSN config" );
		}
		
		if ( empty( $dsn[ 'port' ] ) && ! empty( $dsn[ 'driver' ] ) ) {
			$dsn[ 'port' ] = self::getSchemePort( $dsn[ 'driver' ] );
		}
		
		$dsn[ 'password' ] = base64_encode($dsn['password']);

		$patterns = [ 
				"/(<driver>)/",
				"/(<username>)/",
				"/(<password>)/",
				"/(<host>)/",
				"/(<port>)/",
				"/(<database>)/",
				"/(<charset>)/" 
		];
		
		$replacements = [ 
				$dsn[ 'driver' ],
				$dsn[ 'username' ],
				$dsn[ 'password' ],
				$dsn[ 'host' ],
				$dsn[ 'port' ],
				$dsn[ 'database' ] 
		];
		
		$dsn_string = preg_replace( $patterns, $replacements, "<driver>://<username>:<password>@<host>:<port>/<database>" );
		
		$dsn_instance = new Dsn( $dsn_string );
		$dsn_instance->password_encoded = true;
		$dsn_instance->setName( $dsn[ 'name' ] );
		
		if ( ! empty( $dsn[ 'encryption_key' ] ) ) {
			$dsn_instance->setEncryptionKey( $dsn[ 'encryption_key' ] );
		}
		
		if ( ! empty( $dsn[ 'charset' ] ) ) {
			$dsn_instance->setCharSet( $dsn[ 'charset' ] );
		}
		
		if ( ! empty( $dsn[ 'information_schema' ] ) ) {
			$dsn_instance->setInformationSchema( $dsn[ 'information_schema' ] );
		}
		
		return $dsn_instance;
	}
}
