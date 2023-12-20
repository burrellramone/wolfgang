<?php

namespace Wolfgang\Interfaces\SQL;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IUnduplicable {
	
	/**
	 *
	 * @return array
	 */
	public function getUpdateColumns ( ): array;
}