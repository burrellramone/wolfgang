<?php

namespace Wolfgang\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Network\Uri\UriPath;
use Wolfgang\Routing\HttpRoute;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class HttpRouter extends Router {
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

		if( !($request instanceof IHttpRequest) ){
			throw new InvalidArgumentException("Request must be an instance of Wolfgang\Interfaces\Message\HTTP\IRequest");
		}

		$route = new HttpRoute();
		$route->setMethod( $request->getMethod() );
		$route->setUri( $request->getUri() );

		$this->route = $route;
		
		return $this->route;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Routing\Router::setApplication()
	 */
	public function setApplication ( IApplication $application ) {
		$this->application = $application;
	}
	
	public function delete ( UriPath $path ) {
	}
	
	public function put ( UriPath $path ) {
	}
	
	public function head ( UriPath $path ) {
	}
	
	public function trace ( UriPath $path ) {
	}
	
	public function post ( UriPath $path ) {
	}
	
	public function get ( UriPath $path ) {
	}
	

	public function options ( UriPath $path ) {
	}
	

	public function connect ( UriPath $path ) {
	}
}