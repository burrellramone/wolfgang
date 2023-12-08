<?php

namespace Wolfgang\Controller;

use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Message\CLI\IRequest as ICliRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Auth\ApiController as ApiControllerAuthenticator;
use Wolfgang\Dispatching\EventDispatcher;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @uses Wolfgang\Controller\Controller
 * @uses Wolfgang\Interfaces\HTTP\IRequest
 * @uses Wolfgang\Interfaces\HTTP\IResponse
 * @since Version 0.1.0
 */
abstract class Cli extends Controller {

	/**
	 *
	 * @access public
	 * @param IRequest $request
	 * @param IResponse $response
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IRequest $request, IResponse $response ) {
		if ( ! ($request instanceof ICliRequest) ) {
			throw new InvalidArgumentException( "Request is not an instance of Wolfgang\Interfaces\CLI\IRequest" );
		}

		parent::__construct( $request, $response );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Controller\Controller::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->authenticator = new ApiControllerAuthenticator( $this );
		$this->setEventDispatcher( EventDispatcher::getInstance() );
	}
}
