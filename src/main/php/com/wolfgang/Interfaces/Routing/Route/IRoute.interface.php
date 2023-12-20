<?php

namespace Wolfgang\Interfaces\Routing\Route;

use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Interfaces\Routing\IRouter;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IRoute {

	/**
	 *
	 * @return IRouter
	 */
	public function getRouter ( ): IRouter;

	/**
	 *
	 * @return string
	 */
	public function getMethod ( ): string;

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