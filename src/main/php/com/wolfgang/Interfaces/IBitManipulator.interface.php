<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
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
