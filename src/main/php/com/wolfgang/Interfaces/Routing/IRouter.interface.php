<?php

namespace Wolfgang\Interfaces\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Network\Uri\UriPath;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IRouter {
	const DEFAULT_CONTROLLER = 'Index';
	const DEFAULT_ACTION = 'index';

	/**
	 *
	 * @param UriPath $uri
	 */
	public function get ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function post ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function put ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function delete ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function trace ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function connect ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function options ( UriPath $path );

	/**
	 *
	 * @param UriPath $uri
	 */
	public function head ( UriPath $path );

	/**
	 * Gets the active route that is currently being executed in this router
	 *
	 * @return IRoute
	 */
	public function getRoute ( ): IRoute;

	/**
	 *
	 * @param IRoute $route
	 */
	public function addRoute ( IRoute $route );

	/**
	 *
	 * @return IApplication
	 */
	public function getApplication ( ): IApplication;
}
