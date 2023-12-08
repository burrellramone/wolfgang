<?php

namespace Wolfgang\Exceptions\Routing;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0
 */
final class RouteException extends Exception {

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