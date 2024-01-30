<?php

namespace Wolfgang\Auth;

use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\Message\HTTP\UnauthorizedException;
use Wolfgang\Session\Session;
use Wolfgang\Util\Bots;
use Wolfgang\Interfaces\Message\HTTP\IApiRequest;
use Wolfgang\Interfaces\Controller\IApiController;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;

/**
 * Controller authentication component. This class authorizes access to an api controller within the
 * framework
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class ApiController extends Controller {

	/**
	 *
	 * @param IController $controller
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IController $controller ) {
		if ( ! ($controller instanceof IApiController) ) {
			throw new InvalidArgumentException( "Controller must implement Wolfgang\Interfaces\Controller\IApiController" );
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
	 * @param IRequest $request
	 * @param IRoute $route
	 * @throws InvalidArgumentException
	 * @throws UnauthorizedException
	 * @return boolean
	 */
	public function authenticate ( IRequest $request, IRoute $route ): bool {
		if ( ! ($request instanceof IApiRequest) ) {
			throw new InvalidArgumentException( 'Request is not an instance of the Wolfgang\Interfaces\Message\HTTP\IApiRequest' );
		}

		if ( $request->getMethod() == IHttpRequest::METHOD_OPTIONS ) {
			return true;
		}

		if ( ! Session::getInstance()->get( 'user_id' ) ) {
			if ( ! $this->isAllowed( $route->getAction() ) && empty( $request->getApiKey() ) ) {
				throw new UnauthorizedException( "Unauthorized HTTP API request to {$request->getUri()}" );
			}
		}

		if ( Bots::isBot() && ! Bots::isAllowedBot() ) {
			throw new UnauthorizedException();
		}

		return true;
	}
}