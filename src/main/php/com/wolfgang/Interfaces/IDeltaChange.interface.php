<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Wolfgang\Interfaces
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface IDeltaChange {
	
	public function getProperty ( ): string;
	
	public function getOldValue ( );
	
	public function getNewValue ( );
}
