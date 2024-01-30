<?php

namespace Wolfgang\Interfaces\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\IRoute;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IRouter {
	const DEFAULT_CONTROLLER = 'Index';
	const DEFAULT_ACTION = 'index';

	/**
	 * Gets the active route that is currently being executed in this router
	 *
	 * @return IRoute
	 */
	public function getRoute ( ): IRoute;

	/**
	 *
	 * @return IApplication
	 */
	public function getApplication ( ): IApplication;
}
