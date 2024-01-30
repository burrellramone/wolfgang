<?php

namespace Wolfgang\Routing;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Application\ISite;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Routing\CliRoute;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class CliRouter extends Router {
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
		$route = new CliRoute();
		
		$this->route = $route;
		
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
}