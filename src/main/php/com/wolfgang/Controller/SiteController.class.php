<?php

namespace Wolfgang\Controller;

use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Auth\Controller as ControllerAuthenticator;
use Wolfgang\Dispatching\EventDispatcher;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Controller\Controller
 * @uses Wolfgang\Interfaces\Message\IRequest
 * @uses Wolfgang\Interfaces\Message\IResponse
 * @uses Wolfgang\Auth\Controller
 * @since Version 0.1.0
 */
abstract class SiteController extends Controller {

	/**
	 *
	 * @access public
	 * @param IRequest $request
	 * @param IResponse $response
	 */
	public function __construct ( IRequest $request, IResponse $response ) {
		parent::__construct( $request, $response );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Controller\Controller::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->authenticator = new ControllerAuthenticator( $this );
		$this->setEventDispatcher( EventDispatcher::getInstance() );
	}
}
