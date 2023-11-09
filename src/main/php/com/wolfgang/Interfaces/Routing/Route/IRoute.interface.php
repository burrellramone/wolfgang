<?php

namespace Wolfgang\Interfaces\Routing\Route;

use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Interfaces\Routing\IRouter;

/**
 *
 * @package Wolfgang\Interfaces\Routing\Route
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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