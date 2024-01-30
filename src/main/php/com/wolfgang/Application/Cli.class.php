<?php

namespace Wolfgang\Application;

//PHP
use Exception;

//Wolfgang
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IMessage;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Message\CLI\Response as CliResponse;
use Wolfgang\Message\CLI\Request as CliRequest;
use Wolfgang\Routing\CliRouter;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Application\Application
 * @uses Wolfgang\Interfaces\ISingleton
 * @since Version 0.1.0
 */
final class Cli extends Application {
	//use TSingleton;

	/**
	 *
	 * @throws InvalidStateException
	 */
	protected function __construct (IContext $context) {
		parent::__construct( IApplication::KIND_CLI, $context);
	}

	final public static function getInstance() : ISingleton {
		if ( self::$instance == null ) {
			self::$instance = new static(Context::getInstance());
		}

		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->setRouter( CliRouter::getInstance() );
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
		return $this->respond;
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
		if ( ! ($request instanceof CliRequest) ) {
			throw new InvalidArgumentException( "Message must be an instance of Wolfgang\Message\CLI\Request" );
		}

		$this->setRequest( $request );

		try {
			$driver_manager = $this->getDriverManager();
			$driver_manager->begin();

			$this->getDispatcher()->dispatch( $request, $this->getRouter()->route( $request ) );

			$driver_manager->commit();
		} catch ( Exception $e ) {
			$this->response->setBody($e->getMessage() . "\n\n" . $e->getTraceAsString());
		}

		return $this->response;
	}
}
