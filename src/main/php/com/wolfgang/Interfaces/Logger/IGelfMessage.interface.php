<?php

namespace Wolfgang\Interfaces\Logger;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IGelfMessage {

	/**
	 *
	 * @return string
	 */
	public function getVersion ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getHost ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getShortMessage ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getFullMessage ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getTimestamp ( ): string;

	/**
	 *
	 * @return int
	 */
	public function getLevel ( ): int;
}
