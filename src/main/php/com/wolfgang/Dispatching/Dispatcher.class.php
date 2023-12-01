<?php

namespace Wolfgang\Dispatching;

use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Interfaces\Message\HTTP\IRequest;
use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Dispatching\IDispatcher;
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Encoding\JSON;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\Model\IModelList;
use Wolfgang\Util\DataTableMarshaller;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\IGraph;
use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Dispatching
 * @since Version 1.0.0
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
	 * @author Ramone Burrell <burrellramone@gmail.com>
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Dispatching\IDispatcher::dispatch()
	 */
	public function dispatch ( IRequest $request, IRoute $route ): void {
		$route->getController()->getAuthenticator()->authenticate( $request, $route );

		if ( $request->getMethod() == IRequest::METHOD_OPTIONS ) {
			return;
		}

		if ( $route->methodExists() ) {

			$result = $route->getController()->{$route->getAction()}();

			if ( $result ) {

				if ( is_object( $result ) ) {

					if ( ($result instanceof IMarshallable) ) {

						if ( ($result instanceof IModel) ) {
							$result = DataTableMarshaller::recursiveMarshall( $result );
						} else if ( ($result instanceof IModelList) ) {
							if ( $request->getHeader( 'HTTP-X-REQUESTED-WITH-DATATABLE' ) ) {
								$result = DataTableMarshaller::getInstance()->marshall( $result );
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
						throw new IllegalStateException( "Object returned but it does not implement Wolfgang\Interfaces\IMarshallable, Wolfgang\Interfaces\Model\IMarshallable nor \JsonSerializable" );
					}
				} else if ( is_array( $result ) ) {
					$result = DataTableMarshaller::recursiveMarshall( $result );
				}

				$this->getApplication()->getResponse()->setData( $result );
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
