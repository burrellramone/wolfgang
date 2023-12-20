<?php

namespace Wolfgang\Controller;

use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Message\HTTP\IApiRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Auth\ApiController as ApiControllerAuthenticator;
use Wolfgang\Dispatching\EventDispatcher;
use Wolfgang\Interfaces\Controller\IApiController;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Controller\Controller
 * @uses Wolfgang\Auth\ApiController
 * @uses Wolfgang\Interfaces\Message\IRequest
 * @uses Wolfgang\Interfaces\Message\IResponse
 * @since Version 0.1.0
 */
abstract class ApiController extends Controller implements IApiController {
	
	/**
	 *
	 * @access public
	 * @param IRequest $request
	 * @param IResponse $response
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IRequest $request, IResponse $response ) {
		if ( ! ($request instanceof IApiRequest) ) {
			throw new InvalidArgumentException( "Request is not an instance of Wolfgang\Interfaces\HTTP\IApiRequest" );
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
