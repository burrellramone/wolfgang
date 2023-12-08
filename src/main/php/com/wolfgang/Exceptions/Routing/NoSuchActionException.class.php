<?php

namespace Wolfgang\Exceptions\Routing;

/**
 *
* @since Version 0.1.0
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