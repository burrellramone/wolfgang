<?php

namespace Wolfgang\Exceptions\Message\HTTP;

use Wolfgang\Interfaces\Message\HTTP\IResponse;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class UnauthorizedException extends Exception {
	/**
	 *
	 * @var int
	 */
	protected $http_code = IResponse::STATUS_CODE_UNAUTHORIZED;

	/**
	 *
	 * @var string
	 */
	protected $http_status = 'Unauthorized';

	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param string $previous
	 */
	public function __construct ( $message = "401 Unauthorized. See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html", $code = 0, $previous = NULL ) {
		parent::__construct( $message, $code, $previous );
	}
}
