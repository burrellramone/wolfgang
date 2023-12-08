<?php

namespace Wolfgang\Exceptions\Database;

/**
 *
 * @filesource Exceptions\SQL\UnattainableConnectionException
* @since Version 1.0
 */
final class UnattainableConnectionException extends Exception {

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