<?php

namespace Wolfgang\Auth;

use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\UnauthorizedException;
use Wolfgang\Interfaces\Controller\ICliController;

/**
 * Controller authentication component. This class authorizes access to an api controller within the
 * framework
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class CliController extends Controller {

	/**
	 *
	 * @param IController $controller
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IController $controller ) {
		if ( ! ($controller instanceof ICliController) ) {
			throw new InvalidArgumentException( "Controller must implement Wolfgang\Interfaces\Controller\ICliController" );
		}

		parent::__construct( $controller );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Auth\Controller::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Auth\Controller::authenticate()
	 */
	public function authenticate ( IRequest $request, IRoute $route ): bool {
		throw new UnauthorizedException( "Implement this shit" );

		return true;
	}
}