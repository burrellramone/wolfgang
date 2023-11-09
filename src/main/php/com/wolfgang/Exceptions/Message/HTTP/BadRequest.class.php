<?php

namespace Wolfgang\Exceptions\Message\HTTP;

/**
 *
 * @package Wolfgang\Exceptions\Message\HTTP
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0
 */
final class BadRequest extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = 400;
	
	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "400 Bad Request. See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
		
	}
}
