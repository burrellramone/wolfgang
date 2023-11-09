<?php

namespace Wolfgang\Routing\Route;

use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Routing\ApiRouter;
use Wolfgang\Interfaces\Routing\Route\IApiRoute;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0
 */
final class ApiRoute extends Route implements IApiRoute {

	/**
	 *
	 * @param IRouter $router
	 * @param string $method
	 * @param string $uri
	 */
	public function __construct ( IRouter $router, $method, $uri ) {
		parent::__construct( $router, $method, $uri );
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \Wolfgang\Routing\Route\Route::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Routing\Route\Route::setRouter()
	 */
	protected function setRouter ( IRouter $router ) {
		if ( ! ($router instanceof ApiRouter) ) {
			throw new InvalidArgumentException( "Router is not an instance of Wolfgang\Routing\ApiRouter" );
		}

		$this->router = $router;
	}
}
