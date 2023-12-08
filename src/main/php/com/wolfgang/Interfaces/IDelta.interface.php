<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IDelta {
	
	public function getAffectedProperties ( ): array;
}
