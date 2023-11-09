<?php

namespace Wolfgang\Exceptions\Filesystem;

/**
 *
 * @package Components
* @uses Wolfgang\Exceptions\Filesystem\Exception
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class FileNotExistException extends Exception {
	
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