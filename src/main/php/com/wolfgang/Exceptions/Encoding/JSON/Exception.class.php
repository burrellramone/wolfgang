<?php

namespace Wolfgang\Exceptions\Encoding\JSON;

use Wolfgang\Exceptions\Encoding\Exception as EncodingException;

/**
 *
 * @package Exceptions
* @since Version 1.0
 */
class Exception extends EncodingException {
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