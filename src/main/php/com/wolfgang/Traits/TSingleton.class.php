<?php

namespace Wolfgang\Traits;

use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TSingleton {
	
	/**
	 *
	 * @var ISingleton
	 */
	protected static $instance;
	
	/**
	 *
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( self::$instance == null ) {
			self::$instance = new static();
		}
		
		return self::$instance;
	}
}
