<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IDeltaChange {
	
	public function getProperty ( ): string;
	
	public function getOldValue ( );
	
	public function getNewValue ( );
}
