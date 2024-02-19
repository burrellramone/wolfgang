<?php

namespace Wolfgang\Application;

//PHP
use Exception;
use Error;
use ErrorException;

//Wolfgang
use Wolfgang\Interfaces\Message\HTTP\IResponse as IHttpResponse;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;
use Wolfgang\Message\HTTP\Response as HttpResponse;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\Application\ISite;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Templating\Templater;
use Wolfgang\Routing\HttpRouter;
use Wolfgang\Util\Logger\Logger;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @abstract
 * @since Version 0.1.0
 */
abstract class Site extends Application implements ISite {

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

		$session = $this->getSession();

		if( $session ){ 
			$errors = $session->get('errors');
			$warnings = $session->get('warnings');
			$notices = $session->get('notices');

			if ( $errors ) {
				$this->setErrors(unserialize( $errors ));
			}
	
			if ( $warnings ) {
				$this->setWarnings(unserialize( $warnings ));
			}
	
			if ( $notices ) {
				$this->setNotices(unserialize( $notices ));
			}
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
		$session = $this->getSession();

		if ($session) { 
			$session->set('errors', serialize( $this->getErrors() ));
			$session->set('notices', serialize( $this->getNotices() ));
			$session->set('warnings', serialize( $this->getWarnings() ));
		}

		// Before ending execution and redirecting the user, commit all open transaction
		$this->getDriverManager()->commit();

		//MUST write and close session here before 'respond' is called since 'respond' will result in output being written
		session_write_close();

		$this->getResponse()->setHeader( "Location", $uri );
		$this->respond();
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

			if ( ! $this->response->isError() ) {
				$driver_manager->commit();
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
	}
}
