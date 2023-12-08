<?php

namespace Wolfgang\Exceptions\SQL\Clause;

use Wolfgang\Exceptions\SQL\Exception as SQLException;

/**
 *
* @since Version 0.1.0
 */
class Exception extends SQLException {

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