<?php

namespace Wolfgang\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Application\ISite;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Network\Uri\UriPath;
use Wolfgang\Routing\Route\SiteRoute;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Routing\Route\ISiteRoute;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Interfaces\Message\HTTP\IRequest;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class SiteRouter extends Router {
	use TSingleton;
	
	/**
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
		$route = null;
		$uri_parts = explode( '/', $request->getUri() );
		
		foreach ( $this->getRoutes() as $route ) {
			if ( $route->getController() == $uri_parts[ 0 ] && $route->getAction() == $uri_parts[ 1 ] ) {
				$this->route = $route;
			}
		}
		
		if ( ! $this->route ) {
			$route = new SiteRoute( $this, $request->getMethod(), $request->getUri() );
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
		if ( ! ($application instanceof ISite) ) {
			throw new IllegalArgumentException( "Application is not an instance of Wolfgang\Interfaces\Application\ISite" );
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
		if ( ! ($route instanceof ISiteRoute) ) {
			throw new InvalidArgumentException( "Route is not an instance of Wolfgang\Interfaces\Routing\Route\ISiteRoute" );
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