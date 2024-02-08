<?php

namespace Wolfgang\Routing;

use Wolfgang\Application\Application;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Util\Strings;
use Wolfgang\Exceptions\Routing\NoSuchActionException;
use Wolfgang\Exceptions\Routing\Exception as RoutingException;
use Wolfgang\Exceptions\Message\HTTP\NotFoundException;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class HttpRoute extends Route {

	/**
	 *
	 * @param IRouter $router
	 * @param string $method
	 * @param string $uri
	 */
	public function __construct () {
		parent::__construct( );
	}

	/**
	 *
	 * @throws NoSuchActionException
	 * @throws RoutingException
	 * @throws NotFoundException
	 * {@inheritDoc}
	 * @see \Wolfgang\Routing\Route\Route::init()
	 */
	protected function init ( ) {
		parent::init();
	
		$application = Application::getInstance();
		$context = $application->getContext();
		$app = $context->getApplication();
		$controller = $context->getController();
		$action = $context->getAction();

		$applicationKind = Strings::ucwords( $application->getKind() );
		$request = $application->getRequest();
		$response = $application->getResponse();

		$class = "Controller\\{$app}\\{$applicationKind}\\$controller";
		$commonClass = preg_replace( "/({$app})/", "Common", $class, 1 );
		
		try {
			$creator = new \ReflectionClass( $class );
			$controllerInstance = $creator->newInstanceArgs( [ 
					$request,
					$response
			] );

			if ( ! $this->isTabAction( $action ) && ! method_exists( $controllerInstance, $action ) ) {
				throw new NotFoundException( "Controller '{$class}' has no such action called '{$action}'" );
			}
		} catch ( NotFoundException $e1 ) {
			try {
				$creator = new \ReflectionClass( $commonClass );
				$controllerInstance = $creator->newInstanceArgs( [ 
						$request,
						$response
				] );
			} catch ( \Exception $e ) {
				throw new NotFoundException( "Common controller class '{$controller}' does not exist.", 0, $e1 );
			}
		} catch ( \ReflectionException $e ) {
			try {
				$creator = new \ReflectionClass( $commonClass );

				$controllerInstance = $creator->newInstanceArgs( [ 
						$request,
						$response
				] );

				if ( ! $this->isTabAction( $action ) && ! method_exists( $controllerInstance, $action ) ) {
					throw new NotFoundException( "Controller '{$class}' has no such action '{$action}'", 0, $e );
				}
			} catch ( \ReflectionException $e1 ) {
				throw new NotFoundException( $e->getMessage(), 0, $e1 );
			}
		}

		if ( ! $this->isTabAction( $action ) && ! method_exists( $controllerInstance, $action ) ) {
			throw new NotFoundException( "Controller '{$class}' has no such action '{$action}'" );
		}

		$this->setController( $controllerInstance );
		$this->setAction( $action );
	}

	/**
	 *
	 * @param string $method
	 */
	final public function setMethod ( $method ) {
		$this->method = $method;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\IHttpRoute::getMethod()
	 */
	public function getMethod ( ): string {
		return $this->method;
	}

	/**
	 *
	 * @param IUri $uri
	 */
	final public function setUri ( IUri $uri ) {
		$this->uri = $uri;
	}

	/**
	 *
	 * @return IUri
	 */
	public function getUri ( ): IUri {
		return $this->uri;
	}

	/**
	 *
	 * @param string $action
	 * @return bool
	 */
	public function isTabAction ( string $action ): bool {
		return preg_match( "/(edit_tab_)/", $action ) || preg_match( "/(view_tab_)/", $action );
	}

	public function hasTabAction ( ) {
		return $this->isTabAction( $this->getAction() );
	}

}
