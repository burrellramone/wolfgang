<?php

namespace Wolfgang\Interfaces;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface IDeltaChange {
	
	public function getProperty ( ): string;
	
	public function getOldValue ( );
	
	public function getNewValue ( );
}
