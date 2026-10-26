<?php

namespace Wolfgang\Interfaces;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface IDelta {
	
	public function getAffectedProperties ( ): array;
}
