<?php

namespace Wolfgang\Application;

//PHP
use Exception;
use Error;
use ErrorException;

//Wolfgang
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;
use Wolfgang\Interfaces\Message\HTTP\IResponse as IHttpResponse;
use Wolfgang\Interfaces\Application\ISite;
use Wolfgang\Templating\Templater;
use Wolfgang\Exceptions\Message\HTTP\Exception as HTTPException;
use Wolfgang\Util\Logger\Logger;
use Wolfgang\Routing\HttpRouter;
use Wolfgang\Message\HTTP\Response as HttpResponse;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Network\IUri;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @abstract
 * @since Version 0.1.0
 */
abstract class Site extends Application implements ISite {
	//use TSingleton;

	protected function __construct ( IContext $context ) {
		parent::__construct( IApplication::KIND_SITE, $context);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::init()
	 */
	protected function init ( ) {
		parent::init();

		if ( ! empty( $_SESSION[ 'errors' ] ) ) {
			$this->errors = unserialize( $_SESSION[ 'errors' ] );
		}

		if ( ! empty( $_SESSION[ 'notices' ] ) ) {
			$this->notices = unserialize( $_SESSION[ 'notices' ] );
		}

		$this->setRouter( HttpRouter::getInstance() );
		$this->setResponse( HttpResponse::getInstance() );
	}

	/**
	 *
	 * @param IRequest $request
	 */
	protected function setRequest ( IRequest $request ) {
		if ( ! ($request instanceof IHttpRequest) ) {
			throw new InvalidArgumentException( "Request must implement Wolfgang\Interfaces\Message\HTTP\IRequest" );
		}
		$this->request = $request;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setResponse()
	 */
	protected function setResponse ( IResponse $response ) {
		if ( ! ($response instanceof IHttpResponse) ) {
			throw new InvalidArgumentException( "Response must implement Wolfgang\Interfaces\Message\HTTP\IResponse" );
		}

		$this->response = $response;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setRouter()
	 */
	protected function setRouter ( IRouter $router ) {
		if ( ! ($router instanceof HttpRouter) ) {
			throw new InvalidArgumentException( "Router must be an instance of Wolfgang\Routing\HttpRouter" );
		}

		$router->setApplication( $this );

		$this->router = $router;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::redirect()
	 */
	public function redirect ( IUri|string $uri ): void {
		$_SESSION[ 'errors' ] = serialize( $this->getErrors() );
		$_SESSION[ 'notices' ] = serialize( $this->getNotices() );
		$_SESSION[ 'warnings' ] = serialize( $this->getWarnings() );

		// Before ending execution and redirecting the user, commit all open transaction
		$this->getDriverManager()->commit();

		$this->getResponse()->setHeader( "Location", $uri );
		$this->respond();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::respond()
	 */
	public function respond ( $message = null): ?IResponse {
		if ( is_string( $message ) ) {
			$this->getResponse()->setBody( $message );
		}

		if ( ! ($message instanceof \Exception) ) {
			echo $this->getResponse();
			exit();
		}

		$templater = Templater::getInstance();
		$response = HttpResponse::getInstance();
		$e = $message;
		$message = $e->getMessage();

		$response->setStatusCode( 500 );
		$response->setBody( $message );

		header( $response->getStatusLine() );

		$templater->setTemplate( "Common/sections/errors/{$response->getStatusCode()}.tmpl" );
		$templater->assign( "response", $response );
		$templater->assign( "exception", $e );
		$templater->display();

		exit( 1 );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::execute()
	 */
	public function execute ( IRequest $request ): IResponse {
		if ( ! ($request instanceof IHttpRequest) ) {
			throw new InvalidArgumentException( "Request must implement Wolfgang\Interfaces\Message\IRequest" );
		}

		$this->onBeforeExec();

		$this->setRequest( $request );

		try {
			$e = null;
			$templater = Templater::getInstance();
			$driver_manager = $this->getDriverManager();
			$driver_manager->begin();

			$this->getDispatcher()->dispatch( $request, $this->getRouter()->route( $request ) );

			$driver_manager->commit();
			

			if ( ! $this->response->isError() ) {
				$templater->determineLayout();
				$this->response->setBody( $templater->fetch() );
			}
		} catch ( HTTPException $e ) {
			Logger::getLogger()->error( $e );
		} catch ( Exception $e ) {
			Logger::getLogger()->error( $e );
		} catch ( Error $e ) {
			Logger::getLogger()->error( $e );
		} catch ( ErrorException $e ) {
			Logger::getLogger()->error( $e );
		} finally {
			if($e){
				throw $e;
			}
		}

		$this->onAfterExec();

		return $this->response;
	}

	public function __destruct ( ) {
		parent::__destruct();

		$this->clearNotices();
		$this->clearErrors();
		$this->clearWarnings();
	}
}
