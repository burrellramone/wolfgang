<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Encryption\AES;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @uses Wolfgang\Exceptions\IllegalArgumentException
 * @uses Wolfgang\Encryption\AES
 * @since Version 0.1.0
 */
final class Cookie extends Component {

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @param string $expires
	 * @param string $path
	 * @param string $domain
	 * @param string $secure
	 * @param string $httponly
	 * @throws IllegalArgumentException
	 */
	public static function write ( $name, $value, $expires = YEAR_IN_SECONDS, $path = null, $domain = null, $secure = false, $httponly = true ) {
		if ( empty( $name ) ) {
			throw new IllegalArgumentException( 'Name of cookie must be provided' );
		} else if ( empty( $value ) && $value !== null ) {
			throw new IllegalArgumentException( 'Value for cookie to be written must be provided' );
		} else if ( headers_sent() ) {
			return false;
		}

		if ( $value !== null ) {
			$value = AES::encrypt( $value );
		}

		if ( ! setcookie( $name, $value, time() + $expires, $path, $domain, $secure, $httponly ) ) {
			throw new ComponentException( "Failed to write cookie '{$name}'" );
		}

		return true;
	}

	/**
	 *
	 * @param string $name
	 */
	public static function read ( string $name ) {
		$value = null;
		if ( ! empty( $_COOKIE[ $name ] ) ) {
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
