<?php

namespace Wolfgang\Interfaces\Logger;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
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
