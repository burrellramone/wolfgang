<?php

namespace Wolfgang\Interfaces\Stripe;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface IPaymentMethod {

	/**
	 *
	 * @return string
	 */
	public function getStripeCardId ( ): string;
}