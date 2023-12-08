<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IStripeCustomer {

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