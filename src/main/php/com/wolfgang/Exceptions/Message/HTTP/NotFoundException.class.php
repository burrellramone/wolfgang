<?php

namespace Wolfgang\Exceptions\Message\HTTP;

/**
 *
 * @package Wolfgang\Exceptions\Message\HTTP
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class NotFoundException extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = 404;
	
	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "400 Not Found Request. See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
		
	}
}
