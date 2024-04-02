<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IEmailContact extends IContact {
	
	/**
	 *
	 * @return string
	 */
	public function getEmail ( ): string;
}
