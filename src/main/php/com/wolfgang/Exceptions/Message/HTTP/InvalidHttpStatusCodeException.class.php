<?php

namespace Wolfgang\Exceptions\Message\HTTP;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class InvalidHttpStatusCodeException extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = 500;

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