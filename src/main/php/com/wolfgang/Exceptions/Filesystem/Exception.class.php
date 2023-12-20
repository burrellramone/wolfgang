<?php

namespace Wolfgang\Exceptions\Filesystem;

use Wolfgang\Exceptions\Exception as ComponentException;

/**
 *
* @uses Wolfgang\Exceptions\Exception
 * @author Ramone Burrell <ramone@ramoneburrell.com>
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