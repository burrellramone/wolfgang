<?php

namespace Wolfgang\Interfaces\Application;

use Wolfgang\Interfaces\IApiKey;

/**
 *
 * @package Wolfgang\Interfaces\Application
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IApi extends IApplication {
	
	/**
	 *
	 * @return IApiKey
	 */
	public function getApiKey ( ): IApiKey;
}
