<?php

namespace Wolfgang\Routing\Route;

use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Routing\SiteRouter;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Routing\Route\ISiteRoute;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class SiteRoute extends Route implements ISiteRoute {

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
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Routing\Route\Route::setRouter()
	 */
	protected function setRouter ( IRouter $router ) {
		if ( ! ($router instanceof SiteRouter) ) {
			throw new InvalidArgumentException( "Router is not an instance of Wolfgang\Routing\SiteRouter" );
		}

		$this->router = $router;
	}
}
