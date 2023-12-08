<?php

namespace Wolfgang\Exceptions\ORM;

use Wolfgang\Exceptions\ORM\Exception as ORMException;

/**
 *
 * 
 *
 * @since Version 0.1.0
 */
final class TableNotExistException extends ORMException {
	
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