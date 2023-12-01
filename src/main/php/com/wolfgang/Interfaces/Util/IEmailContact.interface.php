<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface IEmailContact {
	
	/**
	 *
	 * @return string
	 */
	public function getEmail ( ): string;
}
