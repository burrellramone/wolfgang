<?php

namespace Wolfgang\Controller;

use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Component as BaseComponent;
use Wolfgang\Interfaces\Dispatching\IEventDispatcher;
use Wolfgang\Interfaces\IControllerAuthenticator;
use Wolfgang\Dispatching\EventDispatcher;
use Wolfgang\Application\Application;

/**
 *
 * @package Wolfgang\Controller
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @uses Wolfgang\Interfaces\IController
 * @since Version 1.0.0
 */
abstract class Controller extends BaseComponent implements IController {

	/**
	 *
	 * @var IControllerAuthenticator
	 */
	protected $authenticator;

	/**
	 *
	 * @var IRequest
	 */
	protected $request;

	/**
	 *
	 * @var IResponse
	 */
	protected $response;

	/**
	 *
	 * @var EventDispatcher
	 */
	protected $event_dispatcher;

	/**
	 *
	 * @param IRequest $request
	 * @param IResponse $response
	 */
	public function __construct ( IRequest $request, IResponse $response ) {
		$this->authenticate();
		$this->setRequest( $request );
		$this->setResponse( $response );

		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 */
	protected function authenticate ( ): void {
	}

	/**
	 *
	 * @return string
	 */
	public function getShortName ( ): string {
		$reflector = new \ReflectionClass( get_class( $this ) );
		return $reflector->getShortName();
	}

	/**
	 *
	 * @return IControllerAuthenticator
	 */
	public function getAuthenticator ( ): IControllerAuthenticator {
		return $this->authenticator;
	}

	/**
	 *
	 * @param IRequest $request
	 */
	private function setRequest ( IRequest $request ) {
		$this->request = $request;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Controller\IController::getRequest()
	 */
	public function getRequest ( ): IRequest {
		return $this->request;
	}

	/**
	 *
	 * @param IResponse $response
	 */
	private function setResponse ( IResponse $response ) {
		$this->response = $response;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Controller\IController::getResponse()
	 */
	public function getResponse ( ): IResponse {
		return $this->response;
	}

	/**
	 * Gets the id match in the URL path of the request
	 *
	 * @return string|NULL The id matched in the URL of the request, null if it was not matched
	 */
	public function getIdMatched ( ): ?string {
		return Application::getInstance()->getContext()->getIdMatched();
	}

	/**
	 *
	 * @param IEventDispatcher $event_dispatcher
	 */
	public function setEventDispatcher ( IEventDispatcher $event_dispatcher ) {
		$this->event_dispatcher = $event_dispatcher;
	}
}
