<?php

namespace Wolfgang\Interfaces\Dispatching;

use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Interfaces\Message\HTTP\IRequest;
use Wolfgang\Interfaces\Application\IApplication;

interface IDispatcher {

	/**
	 *
	 * @param IRequest $request
	 * @param IRoute $route
	 */
	public function dispatch ( IRequest $request, IRoute $route );

	/**
	 *
	 * @param IApplication $application
	 */
	public function setApplication ( IApplication $application );

	/**
	 *
	 * @return IApplication
	 */
	public function getApplication ( ): IApplication;
}
