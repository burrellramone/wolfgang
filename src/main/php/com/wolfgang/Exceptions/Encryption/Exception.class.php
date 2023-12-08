<?php

namespace Wolfgang\Exceptions\Encryption;

use Wolfgang\Exceptions\Exception as ComponentException;

/**
 *
* @since Version 0.1.0
 */
class Exception extends ComponentException {

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