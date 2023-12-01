<?php

namespace Wolfgang\Exceptions\Message\HTTP;

/**
 *
 * @package Wolfgang\Exceptions\Message\HTTP
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class InternalServerError extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = 500;
	
	/**
	 *
	 * @var string
	 */
	protected $http_status = 'Internal Server Error';
	
	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "500 Internal Server Error. See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
		
	}
}