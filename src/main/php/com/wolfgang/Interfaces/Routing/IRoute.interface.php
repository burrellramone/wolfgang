<?php

namespace Wolfgang\Interfaces\Routing;

use Wolfgang\Interfaces\Controller\IController;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IRoute {

	/**
	 *
	 * @return IController
	 */
	public function getController ( ): IController;

	/**
	 *
	 * @return string
	 */
	public function getAction ( ): string;
}