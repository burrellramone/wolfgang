<?php

namespace Wolfgang\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Network\Uri\UriPath;
use Wolfgang\Application\Context;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Exceptions\Message\HTTP\BadRequest;

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

		$context = Context::getInstance();
		
		$requestMethod = $request->getMethod();
        $requestPath = $request->getUri()->getPath();
		$site = $context->getSite();
        $routes = $context->isApiDomain() ? $site->getApiDomainRoutes() : $site->getDomainRoutes();
        

        foreach($routes as $method => $methodRoutes){
            foreach($methodRoutes as $siteRoute){
                $type = $siteRoute['type']??Route::ROUTE_TYPE_LITERAL;
                
                switch($type){
                    case Route::ROUTE_TYPE_LITERAL:
                        if ($siteRoute['path'] == $requestPath) {
                            if($method != $requestMethod){
                                throw new BadRequest("HTTP method '{$method}' required to access this resource.");
                            }
                        }
                        break;
                        
                    case Route::ROUTE_TYPE_REGEX:
                        $siteRoutePathQuoted = preg_quote($siteRoute['path'], "/");

                        if (preg_match("/({$siteRoutePathQuoted})/", $requestPath)) {
                            if($method != $requestMethod){
                                throw new BadRequest("HTTP method '{$method}' required to access this resource.");
                            }
                        }
                        break;
                        
                    default:
                        
                        throw new InvalidStateException("Invalid route type '{$type}'.");
                        break;
                }
            }
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