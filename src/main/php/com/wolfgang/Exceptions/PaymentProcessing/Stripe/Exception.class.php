<?php

namespace Wolfgang\Exceptions\PaymentProcessing\Stripe;

use Wolfgang\Exceptions\PaymentProcessing\Exception as PaymentProcessingException;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class Exception extends PaymentProcessingException {

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
