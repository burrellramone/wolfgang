<?php

namespace Wolfgang\Interfaces;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IBitManipulator {

	/**
	 *
	 * @param int $bit
	 */
	public function on ( int $bit );

	/**
	 *
	 * @param int $bit
	 */
	public function off ( int $bit );

	/**
	 *
	 * @param int $bit
	 * @return bool
	 */
	public function isOn ( int $bit ): bool;

	/**
	 *
	 * @param int $bit
	 * @return bool
	 */
	public function isOff ( int $bit ): bool;
}
