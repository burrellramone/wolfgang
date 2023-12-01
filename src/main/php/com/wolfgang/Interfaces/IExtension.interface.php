<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IExtension {

	/**
	 * Gets an instance of the subject of this extension
	 *
	 * @return IExtensible
	 */
	public function getSubject ( ): IExtensible;
}
