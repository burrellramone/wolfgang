<?php

namespace Wolfgang\Routing;

//Wolfgang
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Exceptions\Routing\Exception as RoutingException;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Network\Uri\Uri;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Route extends Component implements IRoute {

    const ROUTE_TYPE_LITERAL = 'literal';
    const ROUTE_TYPE_REGEX = 'regex';
    
	/**
	 *
	 * @var string
	 */
	protected $method;

	/**
	 *
	 * @var IRouter
	 */
	protected $router;

	/**
	 *
	 * @var IController
	 */
	protected $controller;

	/**
	 *
	 * @var string
	 */
	protected $action;

	/**
	 *
	 * @var Uri
	 */
	protected $uri;

	/**
	 *
	 * @param IController $controller
	 */
	protected function setController ( IController $controller ) {
		$this->controller = $controller;
	}

	/**
	 *
	 * @return IController
	 */
	public function getController ( ): IController {
		return $this->controller;
	}

	/**
	 *
	 * @param string $action
	 */
	protected function setAction ( string $action ) {
		$this->action = $action;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IRoute::getAction()
	 */
	public function getAction ( ): string {
		return $this->action;
	}

	/**
	 *
	 * @return boolean
	 */
	public function methodExists ( ) {
		return method_exists( $this->getController(), $this->getAction() );
	}
}