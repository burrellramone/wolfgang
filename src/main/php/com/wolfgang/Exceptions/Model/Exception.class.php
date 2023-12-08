<?php

namespace Wolfgang\Exceptions\Model;

use Wolfgang\Exceptions\Exception as WolfgangException;

/**
 *
 * @since Version 0.1.0
 */
class Exception extends WolfgangException {
	
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