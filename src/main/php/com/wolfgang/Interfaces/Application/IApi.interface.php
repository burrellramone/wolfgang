<?php

namespace Wolfgang\Interfaces\Application;

use Wolfgang\Interfaces\IApiKey;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IApi extends IApplication {
	
	/**
	 *
	 * @return IApiKey
	 */
	public function getApiKey ( ): IApiKey;
}
