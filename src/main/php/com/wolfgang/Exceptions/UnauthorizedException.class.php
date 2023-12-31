<?php

namespace Wolfgang\Exceptions;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class UnauthorizedException extends Exception {

	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "Unauthorized", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
	}
}
