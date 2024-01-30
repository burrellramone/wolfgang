<?php

namespace Wolfgang\Exceptions\Message\HTTP;

use Wolfgang\Interfaces\Message\HTTP\IResponse;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class ServiceUnavailable extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = IResponse::STATUS_CODE_SERVICE_UNAVAILABLE;
	
	/**
	 *
	 * @var string
	 */
	protected $http_status = 'Service Unavailable';
	
	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "503 Service Unavailable. See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
		
	}
}