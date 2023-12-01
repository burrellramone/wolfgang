<?php

namespace Wolfgang\Traits;

use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @package Wolfgang\Traits
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
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
