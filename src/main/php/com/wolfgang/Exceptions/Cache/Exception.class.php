<?php

namespace Wolfgang\Exceptions\Cache;

use Wolfgang\Exceptions\Exception as ComponentException;

/**
 *
 * @package Wolfgang\Exceptions\Cache
 * @author Ramone Burrell <ramone.burrell@rairportruns.ca>
 * @uses Wolfgang\Exceptions\Exception
 * @since Version 1.0.0
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
