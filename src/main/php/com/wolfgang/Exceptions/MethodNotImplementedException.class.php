<?php

namespace Wolfgang\Exceptions;

use Exception;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class MethodNotImplementedException extends Exception {
    /**
	 *
	 * @param string $message
	 * @param number $code
	 * @param Exception $previous
	 */
	public function __construct ( $message = "Method not implemented", $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}