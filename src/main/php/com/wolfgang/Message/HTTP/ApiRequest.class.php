<?php

namespace Wolfgang\Message\HTTP;

use Wolfgang\Interfaces\Message\HTTP\IApiRequest;
use Wolfgang\Interfaces\IApiKey;

/**
 *
 * @package Wolfgang\Message\HTTP
 * @uses Wolfgang\Interfaces\Message\HTTP\Request
 * @uses Wolfgang\Interfaces\ISingleton
 * @uses Componenets\Interfaces\HTTP\IApiRequest
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class ApiRequest extends Request implements IApiRequest {

	/**
	 *
	 * @var IApiKey
	 */
	private $api_key;

	protected function __construct ( ) {
		parent::__construct();

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Message\HTTP\Request::init()
	 */
	protected function init ( ) {
		parent::init();

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\HTTP\IApiRequest::isExternal()
	 */
	public function isExternal ( ): bool {
		return ! $this->isInternal();

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\HTTP\IApiRequest::isInternal()
	 */
	public function isInternal ( ): bool {
		if ( ! empty( $this->headers->X_API_KEY ) ) {
			return true;
		}

		return false;

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\HTTP\IApiRequest::getApiKey()
	 */
	public function getApiKey ( ): ?string {
		return $this->getHeader( 'X_API_KEY' );

	}
}
