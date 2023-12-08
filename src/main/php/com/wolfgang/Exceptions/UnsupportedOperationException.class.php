<?php

namespace Wolfgang\Exceptions;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class UnsupportedOperationException extends Exception {

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
