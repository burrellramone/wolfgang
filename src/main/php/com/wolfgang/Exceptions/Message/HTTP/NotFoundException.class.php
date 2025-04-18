<?php

namespace Wolfgang\Exceptions\Message\HTTP;

use Exception as PHPException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class NotFoundException extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = 404;

	/**
	 *
	 * @var string
	 */
	protected $http_status = 'Not Found';
	
	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param PHPException|null $previous
	 */
	public function __construct ( string $message = "404 Not Found. See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html", int $code = 0, PHPException|null $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
		
	}
}
