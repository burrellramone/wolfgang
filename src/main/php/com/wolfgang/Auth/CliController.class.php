<?php

namespace Wolfgang\Auth;

use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\UnauthorizedException;
use Wolfgang\Interfaces\Controller\ICliController;
use Wolfgang\Interfaces\Message\CLI\IRequest as ICliRequest;

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
		if ( ! ($request instanceof ICliRequest) ) {
			throw new InvalidArgumentException( 'Request is not an instance of the Wolfgang\Interfaces\Message\CLI\IRequest' );
		}

		return true;
	}
}