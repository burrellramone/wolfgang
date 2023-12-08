<?php

namespace Wolfgang\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Application\IApi;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Network\Uri\UriPath;
use Wolfgang\Routing\Route\ApiRoute;
use Wolfgang\Interfaces\Routing\Route\IApiRoute;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\Message\HTTP\IRequest;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class ApiRouter extends Router {
	use TSingleton;
	
	/**
	 *
	 * @param IApplication $application
	 */
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Routing\Router::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Routing\Router::route()
	 */
	public function route ( IRequest $request ): IRoute {
		$uri_parts = explode( '/', $request->getUri() );
		
		foreach ( $this->getRoutes() as $route ) {
			if ( $route->getController() == $uri_parts[ 0 ] && $route->getAction() == $uri_parts[ 1 ] ) {
				$this->route = $route;
			}
		}
		
		if ( ! $this->route ) {
			$route = new ApiRoute( $this, $request->getMethod(), $request->getUri() );
			$this->addRoute( $route );
			$this->route = $route;
		}
		
		return $this->route;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Routing\Router::setApplication()
	 */
	public function setApplication ( IApplication $application ) {
		if ( ! ($application instanceof IApi) ) {
			throw new InvalidArgumentException( "Application is not an instance of Wolfgang\Interfaces\Application\IApi" );
		}
		
		$this->application = $application;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Routing\Router::addRoute()
	 */
	public function addRoute ( IRoute $route ) {
		if ( ! ($route instanceof IApiRoute) ) {
			throw new InvalidArgumentException( "Route is not an instance of Wolfgang\Interfaces\Routing\Route\IApiRoute" );
		}
		$this->routes->append( $route );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::delete()
	 */
	public function delete ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::put()
	 */
	public function put ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::head()
	 */
	public function head ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::trace()
	 */
	public function trace ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::post()
	 */
	public function post ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::get()
	 */
	public function get ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::options()
	 */
	public function options ( UriPath $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::connect()
	 */
	public function connect ( UriPath $path ) {
	}
}