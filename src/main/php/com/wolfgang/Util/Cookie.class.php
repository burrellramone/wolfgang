<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Encryption\AES;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Exceptions\InvalidArgumentException
 * @uses Wolfgang\Encryption\AES
 * @since Version 0.1.0
 */
final class Cookie extends Component {

	/**
	 * @see http://php.net/setcookie
	 * @param string $name
	 * @param string $value
	 * @param string $expires
	 * @param string $path
	 * @param string $domain
	 * @param bool $secure
	 * @param bool $httponly
	 * @param bool $encrypt_value
	 * @throws InvalidArgumentException
	 */
	public static function write ( string $name, string $value, int|null $expires = YEAR_IN_SECONDS, $path = null, $domain = null, bool $secure = true, bool $httponly = true, $encrypt_value = true) {
		if ( empty( $name ) ) {
			throw new InvalidArgumentException( 'Name of cookie must be provided' );
		} else if ( headers_sent() ) {
			return false;
		}

		if($expires){
			$expires += time();
		}

		if($encrypt_value){
			$value = AES::encrypt( $value );
		}

		if ( ! setcookie( $name, $value, $expires, $path, $domain, $secure, $httponly ) ) {
			throw new ComponentException( "Failed to write cookie '{$name}'" );
		}

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @return string|null
	 */
	public static function read ( string $name ):?string {
		$value = null;
		if ( isset( $_COOKIE[ $name ] ) ) {
			$value = AES::decrypt( $_COOKIE[ $name ] );
		}
		return $value;
	}

	/**
	 *
	 * @param string $name
	 */
	public static function remove ( string $name ) {
		if ( isset( $_COOKIE[ $name ] ) ) {
			unset( $_COOKIE[ $name ] );
		}
	}
}
