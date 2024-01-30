<?php

namespace Wolfgang\Message\HTTP;

use Wolfgang\Interfaces\Message\HTTP\IRequest;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Network\Uri\Uri;
use Wolfgang\Traits\Message\TRequest;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Message\HTTP\Message
 * @uses Wolfgang\Interfaces\ISingleton
 * @uses Wolfgang\Interfaces\HTTP\IRequest
 * @since Version 0.1.0
 */
class Request extends Message implements ISingleton , IRequest {
	use TSingleton;
	use TRequest;

	/**
	 *
	 * @var string
	 */
	protected $method;

	/**
	 *
	 * @var IUri
	 */
	protected $uri;

	final protected function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		$_REQUEST = array_merge( array_merge( $_REQUEST, $_POST ), $_GET );
		
		foreach ( $_REQUEST as $param => $value ) {
			$this->params->{$param} = $value;
		}

		$this->headers = getallheaders();
		$this->setMethod( $_SERVER[ 'REQUEST_METHOD' ] );
		$this->setUri( isset( $_SERVER[ 'REDIRECT_URL' ] ) ? new Uri( $_SERVER[ 'REDIRECT_URL' ] ) : new Uri( $_SERVER[ 'REQUEST_URI' ] ) );
	}

	/**
	 *
	 * @param string $method
	 */
	private function setMethod ( string $method ) {
		$this->method = $method;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\HTTP\IRequest::getMethod()
	 */
	public function getMethod ( ): string {
		return $this->method;
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
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\HTTP\IRequest::getUri()
	 */
	public function getUri ( ): IUri {
		return $this->uri;
	}
}
