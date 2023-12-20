<?php

namespace Wolfgang\Exceptions\Message\HTTP;

use Wolfgang\Exceptions\Exception as ComponentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Exception extends ComponentException {

	/**
	 *
	 * @var string
	 */
	protected $message = "HTTP Exception";

	/**
	 *
	 * @var string
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
	public function __construct ( $message = "", $code = 0, $previous = NULL) {
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Get the corresponding HTTP code for this HTTP exception intance
	 *
	 * @return int
	 */
	public function getHttpCode ( ): int {
		return $this->http_code;
	}

	/**
	 */
	public function getHTTPStatus ( ) {
		return $this->http_status;
	}
}
