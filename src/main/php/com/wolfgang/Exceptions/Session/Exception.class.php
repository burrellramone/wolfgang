<?php

namespace Wolfgang\Exceptions\Session;

use Wolfgang\Exceptions\Exception as BaseException;

/**
 *
 * @package Exceptions
* @since Version 1.0
 */
class Exception extends BaseException {

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