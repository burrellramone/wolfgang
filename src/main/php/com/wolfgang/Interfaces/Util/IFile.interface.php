<?php

namespace Wolfgang\Interfaces\Util;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Interfaces\Util
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface IFile {

	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getType ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getPath ( ): string;

	/**
	 *
	 * @return int
	 */
	public function getSize ( ): int;

	/**
	 *
	 * @return string
	 */
	public function getExtension ( ): string;

	/**
	 *
	 * @return int
	 */
	public function getWidth ( ): int;

	/**
	 *
	 * @return int
	 */
	public function getHeight ( ): int;
}