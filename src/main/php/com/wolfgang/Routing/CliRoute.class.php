<?php

namespace Wolfgang\Routing;

use Wolfgang\Application\Application;
use Wolfgang\Interfaces\Routing\ICliRoute;
use Wolfgang\Util\Strings;
use Wolfgang\Exceptions\Routing\NoSuchActionException;
use Wolfgang\Exceptions\Routing\Exception as RoutingException;
use Wolfgang\Exceptions\Message\HTTP\NotFoundException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class CliRoute extends Route implements ICliRoute {

	/**
	 * @var array
	 */
	protected $options = array();

	public function init(){
		parent::init();

		$application = Application::getInstance(); 
		$context = $application->getContext();
		$app = $context->getApplication();
		$controller = $context->getControllerName();
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

			if ( ! method_exists( $controllerInstance, $action ) ) {
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

				if ( ! method_exists( $controllerInstance, $action ) ) {
					throw new NoSuchActionException( "Controller '{$class}' has no such action '{$action}'", 0, $e );
				}
			} catch ( \ReflectionException $e1 ) {
				throw new NotFoundException( $e->getMessage(), 0, $e1 );
			}
		}

		if ( ! method_exists( $controllerInstance, $action ) ) {
			throw new NoSuchActionException( "Controller '{$class}' has no such action '{$action}'" );
		}

		$this->setController( $controllerInstance );
		$this->setAction( $action );
	}
}
