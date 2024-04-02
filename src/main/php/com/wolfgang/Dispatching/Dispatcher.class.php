<?php

namespace Wolfgang\Dispatching;

use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Dispatching\IDispatcher;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Encoding\JSON;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\ICliMarshallable;
use Wolfgang\Interfaces\Model\IModelList;
use Wolfgang\Util\AutoCompleteMarshaller;
use Wolfgang\Util\DataTableMarshaller;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\IGraph;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Interfaces\Message\IRequest;
/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Dispatcher extends Component implements IDispatcher , ISingleton {
	use TSingleton;

	/**
	 *
	 * @var IApplication
	 */
	private $application;

	/**
	 */
	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Dispatching\IDispatcher::dispatch()
	 */
	public function dispatch ( IRequest $request, IRoute $route ): void {
		$route->getController()->getAuthenticator()->authenticate( $request, $route );
		
		if ( $route->methodExists() ) {
			$context = $this->getApplication()->getContext();

			$result = $route->getController()->{$route->getAction()}();

			if( $context->isCli() ) {
				$this->handleCliResult($request, $result);
			} else {
				$this->handleHttpResult($request, $result);
			}
		}
	}

	private function handleHttpResult( $request, mixed $result ){
		if ( $result ) {

			if ( is_object( $result ) ) {

				if ( ($result instanceof IMarshallable) ) {
					if ( ($result instanceof IModel) ) {
						$result = DataTableMarshaller::recursiveMarshall( $result );
					} else if ( ($result instanceof IModelList) ) {
						if ( $request->getHeader( 'HTTP-X-REQUESTED-WITH-DATATABLE' ) ) {
							$result = DataTableMarshaller::getInstance()->marshall( $result );
						} else if($request->getHeader( 'Http-X-Requested-For-AutoComplete' )) {
							$result = AutoCompleteMarshaller::getInstance()->marshall( $result );
						} else {
							$result = $result->marshall();
						}
					} else if ( ($result instanceof IGraph) ) {
						$result = $result->marshall();
					} else {
						throw new Exception( "Could not interpret dispatch response" );
					}
				} else if ( ($result instanceof \JsonSerializable) ) {
					$result = JSON::encode( $result );
				} else {
					throw new IllegalStateException( "Object returned but it does not implement Wolfgang\Interfaces\IMarshallable nor \JsonSerializable" );
				}
			} else if ( is_array( $result ) ) {
				$result = DataTableMarshaller::recursiveMarshall( $result );
			}

			$this->getApplication()->getResponse()->setData( $result );
		}
	}

	private function handleCliResult( $request, mixed $result ){
		if ( $result ) {

			if( is_object($result) ) {
				if( !($result instanceof ICliMarshallable)){
					throw new IllegalStateException( "Object returned but it does not implement Wolfgang\Interfaces\ICliMarshallable" );
				}

				if ( ($result instanceof IModel) ) {
					throw new Exception("Not implemented");
				} else if ( ($result instanceof IModelList) ) {
					$result->climarshall();
				}

			} else if ( is_array( $result ) ) {
				throw new Exception("Not implemented");
			}
		}
	}

	/**
	 *
	 * @param IApplication $application
	 */
	public function setApplication ( IApplication $application ) {
		$this->application = $application;
	}

	/**
	 *
	 * @return IApplication
	 */
	public function getApplication ( ): IApplication {
		return $this->application;
	}
}
