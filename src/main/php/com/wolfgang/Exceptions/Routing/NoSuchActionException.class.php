<?php

namespace Wolfgang\Exceptions\Routing;

/**
 *
 * @package Exceptions
* @since Version 1.0
 */
final class NoSuchActionException extends Exception {

	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
	}
}