<?php

namespace Wolfgang\Traits;

use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TSessionHandler {

	/**
	 * The number of seconds after the session cookie is set that it should expire.
	 * 
	 * @see http://php.net/setcookie
	 * @var int
	 */
	protected int $expires = 0;

	/**
	 * @param int
	 */
	public function setExpires(int $expires):void {
		$this->expires = $expires;
	}

	/**
	 * @return int
	 */
	public function getExpires():int {
		return $this->expires;
	}
}
