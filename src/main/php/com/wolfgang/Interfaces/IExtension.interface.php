<?php

namespace Wolfgang\Interfaces;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IExtension {

	/**
	 * Gets an instance of the subject of this extension
	 *
	 * @return IExtensible
	 */
	public function getSubject ( ): IExtensible;
}
