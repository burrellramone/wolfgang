<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Exception as CoreException;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Password extends Component {

	/**
	 *
	 * @var string
	 */
	private $raw = NULL;

	/**
	 *
	 * @var string
	 */
	private $password = NULL;

	/**
	 *
	 * @param string $password
	 */
	public function __construct ( $password = NULL ) {
		parent::__construct();

		if ( empty( $password ) ) {
			$password = md5( time() );
		}

		$this->set( $password );
		$this->validate();
		$this->hash();
	}

	/**
	 *
	 * @param string $password
	 * @return null
	 */
	private function set ( $password ) {
		$this->raw = $password;
	}

	public function get ( ) {
		// Have to base64 encode it since the string has expression within it that will fuck up the
		// preg_replace operation on save
		return base64_encode( $this->password );
	}

	/**
	 *
	 * @return null
	 */
	private function hash ( ) {
		$this->password = password_hash( $this->getRaw(), PASSWORD_BCRYPT, array (
				"cost" => 10
		) );
	}

	/**
	 */
	public function getRaw ( ) {
		return $this->raw;
	}

	/**
	 *
	 * @throws CoreException
	 */
	private function validate ( ) : void{  
		if ( empty( $this->raw ) ) {
			throw new CoreException( "Invalid password. Password is empty" );
		} else if ( strlen( $this->raw ) < 7 ) {
			throw new CoreException( "Invalid password. Password is too short. Password must be at least 7 characters long" );
		}
	}

	/**
	 * 
	 * @param string $password
	 * @param string $hash
	 * @return boolean
	 */
	public static function verify ( $password, $hash ) : bool {
		return password_verify( $password, base64_decode( $hash ) );
	}

	/**
	 */
	public function __toString ( ) {
		return $this->get();
	}
}
