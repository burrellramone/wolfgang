<?php

namespace Wolfgang\Routing\Route;

//PHP
use \Exception;

//Wolfgang
use Wolfgang\Exceptions\Routing\NoSuchActionException;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Controller\IController;
use Wolfgang\Exceptions\Routing\Exception as RoutingException;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Network\Uri\Uri;
use Wolfgang\Util\Strings;
use Wolfgang\Exceptions\Message\HTTP\NotFoundException;
use Wolfgang\Application\Application;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Route extends Component implements IRoute {

	/**
	 *
	 * @var string
	 */
	protected $method;

	/**
	 *
	 * @var IRouter
	 */
	protected $router;

	/**
	 *
	 * @var IController
	 */
	protected $controller;

	/**
	 *
	 * @var string
	 */
	protected $action;

	/**
	 *
	 * @var Uri
	 */
	protected $uri;

	/**
	 *
	 * @param IRouter $router
	 * @param string $method
	 * @param string $uri
	 * @throws IllegalArgumentException
	 */
	public function __construct ( IRouter $router, $method, IUri $uri ) {
		if ( ! $method ) {
			throw new IllegalArgumentException( 'Illegal argument provided for HTTP method. HTTP method must not be empty' );
		} else if ( ! $uri ) {
			throw new IllegalArgumentException( 'Illegal argument provided for request URI. URI must not be empty' );
		}

		$this->setRouter( $router );
		$this->setMethod( $method );
		$this->setUri( $uri );

		parent::__construct();
	}

	/**
	 *
	 * @throws NoSuchActionException
	 * @throws RoutingException
	 * @throws IllegalStateException
	 */
	protected function init ( ) {
		parent::init();

		$controller = Application::getInstance()->getContext()->getControllerName();
		$action = Application::getInstance()->getContext()->getAction();

		$application = $this->getRouter()->getApplication();
		$context = $application->getContext();
		$app = $context->getSkin()->getName();
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
				throw new NoSuchActionException( "Controller '{$class}' has no such action called '{$action}'" );
			}
		} catch ( NoSuchActionException $e1 ) {
			try {
				$creator = new \ReflectionClass( $commonClass );
				$controllerInstance = $creator->newInstanceArgs( [ 
						$request,
						$response
				] );
			} catch ( \Exception $e ) {
				throw new RoutingException( "Common controller class '{$controller}' does not exist.", 0, $e1 );
			}
		} catch ( \ReflectionException $e ) {
			try {
				$creator = new \ReflectionClass( $commonClass );

				$controllerInstance = $creator->newInstanceArgs( [ 
						$request,
						$response
				] );

				if ( ! $this->isTabAction( $action ) && ! method_exists( $controllerInstance, $action ) ) {
					throw new NoSuchActionException( "Controller '{$class}' has no such action '{$action}'", 0, $e );
				}
			} catch ( \ReflectionException $e1 ) {
				throw new NotFoundException( $e->getMessage(), 0, $e1 );
			}
		}

		if ( ! $this->isTabAction( $action ) && ! method_exists( $controllerInstance, $action ) ) {
			throw new NoSuchActionException( "Controller '{$class}' has no such action '{$action}'" );
		}

		$this->setController( $controllerInstance );
		$this->setAction( $action );
	}

	/**
	 *
	 * @param string $method
	 */
	private function setMethod ( $method ) {
		$this->method = $method;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\Route\IRoute::getMethod()
	 */
	public function getMethod ( ): string {
		return $this->method;
	}

	/**
	 *
	 * @param IRouter $router
	 */
	private function setRouter ( IRouter $router ) {
		$this->router = $router;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\Route\IRoute::getRouter()
	 */
	public function getRouter ( ): IRouter {
		return $this->router;
	}

	/**
	 *
	 * @param IController $controller
	 */
	private function setController ( IController $controller ) {
		$this->controller = $controller;
	}

	/**
	 *
	 * @return IController
	 */
	public function getController ( ): IController {
		return $this->controller;
	}

	/**
	 *
	 * @param string $action
	 */
	private function setAction ( string $action ) {
		$this->action = $action;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Routing\Route\IRoute::getAction()
	 */
	public function getAction ( ): string {
		return $this->action;
	}

	/**
	 *
	 * @param IUri $uri
	 */
	private function setUri ( IUri $uri ) {
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

	/**
	 *
	 * @return boolean
	 */
	public function methodExists ( ) {
		return method_exists( $this->getController(), $this->getAction() );
	}
}
