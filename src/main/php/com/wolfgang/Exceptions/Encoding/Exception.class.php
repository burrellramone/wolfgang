<?php

namespace Wolfgang\Exceptions\Encoding;

use Wolfgang\Exceptions\Exception as BaseException;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
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