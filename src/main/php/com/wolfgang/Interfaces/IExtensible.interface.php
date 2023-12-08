<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IExtensible {

	/**
	 * Gets an instance of the extension for this instance
	 *
	 * @return IExtension
	 */
	public function getExtension ( ): IExtension;

	/**
	 * Gets the qualified class name of this class's extension
	 *
	 * @return string
	 */
	public static function getExtensionClass ( ): string;
}