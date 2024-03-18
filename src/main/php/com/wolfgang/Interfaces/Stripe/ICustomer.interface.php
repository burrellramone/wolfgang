<?php

namespace Wolfgang\Interfaces\Stripe;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface ICustomer {

	/**
	 *
	 * @return string
	 */
	public function getStripeCustomerId ( ): ?string;

	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getEmail ( ): string;
}