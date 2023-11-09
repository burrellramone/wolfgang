<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Wolfgang\Interfaces
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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