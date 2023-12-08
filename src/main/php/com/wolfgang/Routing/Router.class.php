<?php

namespace Wolfgang\Routing;

use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Interfaces\Message\HTTP\IRequest;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0
 */
abstract class Router extends Component implements IRouter , ISingleton {

	/**
	 * The application is router is currently routing an action for
	 *
	 * @var IApplication
	 */
	protected $application;

	/**
	 * The active route that is currently being executed in this router / application
	 *
	 * @see Router::getRoute()
	 * @var IRoute
	 */
	protected $route;

	/**
	 *
	 * @var \ArrayObject
	 */
	protected $routes;

	protected function __construct ( ) {
		parent::__construct();

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->routes = new \ArrayObject();

	}

	/**
	 * Routes a request to the appropriate controller and action with the application
	 *
	 * @return IRoute
	 */
	public abstract function route ( IRequest $request ): IRoute;

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Routing\IRouter::addRoute()
	 */
	abstract public function addRoute ( IRoute $route );

	/**
	 *
	 * @return array
	 */
	public function getRoutes ( ): \ArrayObject {
		return $this->routes;

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::getRoute()
	 */
	public function getRoute ( ): IRoute {
		return $this->route;

	}

	/**
	 *
	 * @param IApplication $application
	 */
	public abstract function setApplication ( IApplication $application );

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRouter::getApplication()
	 */
	public function getApplication ( ): IApplication {
		return $this->application;

	}
}
