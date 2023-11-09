<?php

namespace Wolfgang\Application;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IMessage;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Message\CLI\Response as CliResponse;

/**
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Application
 * @uses Wolfgang\Application\Application
 * @uses Wolfgang\Interfaces\ISingleton
 * @since Version 1.0.0
 */
final class Cli extends Application {
	use TSingleton;

	/**
	 *
	 * @throws InvalidStateException
	 */
	protected function __construct ( ) {
		if ( $_SERVER[ 'USER' ] != 'root' ) {
			throw new InvalidStateException( "CLI application must be invoked by root user" );
		}

		parent::__construct( IApplication::KIND_CLI, IApplication::KIND_CLI );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->setResponse( CliResponse::getInstance() );
	}

	/**
	 *
	 * @return IRouter
	 */
	public function getRouter ( ): IRouter {
		return $this->router;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setRequest()
	 */
	protected function setRequest ( IRequest $request ) {
		$this->request = $request;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setResponse()
	 */
	protected function setResponse ( IResponse $response ) {
		$this->response = $response;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setRouter()
	 */
	protected function setRouter ( IRouter $router ) {
		$this->router = $router;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::respond()
	 */
	public function respond ( $message = null): IResponse {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::redirect()
	 */
	public function redirect ( IUri $uri ): void {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::execute()
	 */
	public function execute ( IMessage $request ): IResponse {
	}
}
