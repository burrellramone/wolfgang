<?php

namespace Wolfgang\Application;

use Error;
use Exception;

//
use Wolfgang\Interfaces\Message\IMessage;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Application\IApi;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Message\HTTP\IApiRequest;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\IApiKey;
use Wolfgang\Encoding\JSON;
use Wolfgang\Exceptions\Message\HTTP\Exception as HTTPException;
use Wolfgang\Util\Logger\Logger;
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Routing\ApiRouter;
use Wolfgang\Message\HTTP\Response as HttpResponse;
use Wolfgang\Interfaces\Message\HTTP\IResponse as IHttpResponse;
use Wolfgang\Interfaces\Message\HTTP\IRequest as IHttpRequest;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Exceptions\UnsupportedOperationException;
use Wolfgang\Interfaces\Network\IUri;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Application
 * @uses Wolfgang\Application\Application
 * @uses Wolfgang\Interfaces\Application\IApi
 * @since Version 1.0.0
 */
final class Api extends Application implements IApi {
	use TSingleton;

	/**
	 *
	 * @var IApiKey
	 */
	private $api_key;

	/**
	 */
	protected function __construct ( ) {
		parent::__construct( IApplication::KIND_API, Context::getInstance()->getSkinDomain()->getSkin()->getName() );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->setRouter( ApiRouter::getInstance() );
		$this->setResponse( HttpResponse::getInstance() );

		if ( ! empty( $_SERVER[ 'HTTP_ORIGIN' ] ) ) {
			$this->response->setHeader( "Access-Control-Allow-Origin", $_SERVER[ 'HTTP_ORIGIN' ] );
			$this->response->setHeader( "Access-Control-Allow-Credentials", "true" );
			$this->response->setHeader( "Access-Control-Allow-Methods", "GET, POST, PATCH, PUT, DELETE, OPTIONS" );
			$this->response->setHeader( "Access-Control-Allow-Headers", "Origin, Cookie, Content-Type, X-Auth-Token, Access-Control-Allow-Origin, X-Requested-With, HTTP-X-REQUESTED-WITH-DATATABLE" );
		}
	}

	/**
	 *
	 * @return IRouter
	 */
	public function getRouter ( ): IRouter {
		return $this->router;
	}

	/**
	 * Sets the API key to be used for the request to be executed
	 */
	private function setAPIKey ( IApiKey $apiKey ): void {
		$this->api_key = $apiKey;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Application\IApi::getAPIKey()
	 */
	public function getAPIKey ( ): IApiKey {
		return $this->api_key;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setRouter()
	 */
	protected function setRouter ( IRouter $router ) {
		if ( ! ($router instanceof ApiRouter) ) {
			throw new InvalidArgumentException( "Router must be and instance of Wolfgang\Routing\ApiRouter" );
		}

		$router->setApplication( $this );
		$this->router = $router;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Application\Application::setRequest()
	 */
	protected function setRequest ( IRequest $request ) {
		if ( ! ($request instanceof IApiRequest) ) {
			throw new InvalidArgumentException( "Request is not an instance of Interfaces\HTTP\IApiRequest" );
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
	 * @see \Wolfgang\Application\Application::redirect()
	 */
	public function redirect ( IUri $uri ): void {
		throw new UnsupportedOperationException();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::respond()
	 */
	public function respond ( $message = null): ?IResponse {
		$error = null;

		if ( ($message instanceof \Exception) || ($message instanceof \Error) || (is_string( $message )) ) {
			if ( is_string( $message ) ) {
				$message = new ComponentException( $message );
				Logger::getLogger()->error( $message );
			} else {
				if ( ! ($message instanceof IMarshallable) ) {
					$message = new ComponentException( "", 0, $message );
				}
				Logger::getLogger()->error( $message );
			}

			$error = $message->marshall();

			if ( Context::getInstance()->isProduction() ) {
				$error[ 'trace' ] = null;
			}
		}

		$request = $this->getRequest();
		$response = $this->getResponse();

		$successful_request = ! preg_match( "/^[54]{1}/", $response->getStatusCode() ) && empty( $error );

		$array_response = [ 
				"success" => $successful_request,
				"message" => $successful_request ? 'Ok' : 'Failed',
				"data" => $response->getData(),
				"error" => $error
		];

		$json_response = JSON::encode( $array_response, JSON_PRETTY_PRINT );

		if ( $request->getHeader( 'Accept' ) == 'application/javascript' || $request->getParameter( '__header_accept' ) == 'application/javascript' || $request->getParameter( 'callback' ) ) {
			$response->setHeader( 'Content-Type', 'application/javascript' );

			if ( $request->getParameter( 'callback' ) ) {
				$response->setBody( $request->getParameter( 'callback' ) . "(" . json_encode( $array_response ) . ");" );
			}
		} else {
			$response->setBody( $json_response );
		}

		$backtrace = debug_backtrace( null, 1 );

		if ( $backtrace[ 0 ][ 'function' ] == '__toString' ) {
			echo $response;
			exit( 0 );
		}

		return $response;
	}

	/**
	 *
	 * @param IRequest $request
	 * @return IResponse
	 */
	public function execute ( IMessage $request ): IResponse {
		if ( ! ($request instanceof IApiRequest) ) {
			throw new InvalidArgumentException( "Message must implement interface Wolfgang\Interfaces\Message\HTTP\IApiRequest" );
		}

		$this->setRequest( $request );

		$e = null;

		try {
			ob_start();

			$driver_manager = $this->getDriverManager();
			$driver_manager->begin();

			$this->getDispatcher()->dispatch( $request, $this->getRouter()->route( $request ) );

			$driver_manager->commit();
		} catch ( HTTPException $e ) {
			$this->response->setStatusCode( $e->getHttpCode() );
		} catch ( Error $e ) {
			$this->response->setStatusCode( IHttpResponse::STATUS_CODE_INTERNAL_SERVER_ERROR );
		} catch ( Exception $e ) {
			$this->response->setStatusCode( IHttpResponse::STATUS_CODE_INTERNAL_SERVER_ERROR );
		} finally {

			if ( $request->getMethod() == IHttpRequest::METHOD_OPTIONS ) {
				echo $this->getResponse();
				exit( 0 );
			}

			return $this->respond( $e );
		}
	}
}
