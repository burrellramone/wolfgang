<?php

namespace Wolfgang\Exceptions\PaymentProcessing\Stripe;

use Wolfgang\Exceptions\PaymentProcessing\Exception as PaymentProcessingException;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0
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
