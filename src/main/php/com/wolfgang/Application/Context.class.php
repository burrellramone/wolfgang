<?php

namespace Wolfgang\Application;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\Model\ISkin;
use Wolfgang\Interfaces\Model\ISkinDomain;
use Wolfgang\Model\SkinDomainList;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Model\WhiteLabel;
use Wolfgang\Model\WhiteLabelList;
use Wolfgang\Interfaces\Model\IWhiteLabel;
use Wolfgang\Network\Uri\Uri;

/**
 *
 * @package Wolfgang\Application
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @uses Wolfgang\Interfaces\ISingleton
 * @since Version 1.0.0
 */
final class Context extends Component implements IContext , ISingleton {
	use TSingleton;

	/**
	 *
	 * @var string
	 */
	private $environment;

	/**
	 *
	 * @var IWhiteLabel
	 */
	private $white_label;

	/**
	 *
	 * @var ISkin
	 */
	private $skin;

	/**
	 *
	 * @var ISkinDomain
	 */
	private $skin_domain;

	/**
	 *
	 * @var string
	 */
	private $controller;

	/**
	 *
	 * @var string
	 */
	private $action;

	/**
	 *
	 * @var string
	 */
	private $id_matched;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		if ( PHP_SAPI == IContext::PHP_SAPI_APACHE2_HANDLER ) {
			$uri = new Uri( isset( $_SERVER[ 'REDIRECT_URL' ] ) ? $_SERVER[ 'REDIRECT_URL' ] : $_SERVER[ 'REQUEST_URI' ] );
			$request_uri_parts = array_values( array_filter( explode( '/', $uri->getPath() ) ) );

			if ( empty( $request_uri_parts[ 0 ] ) ) {
				$this->setController( 'Index' );
			} else {
				$this->setController( $request_uri_parts[ 0 ] );
			}

			if ( empty( $request_uri_parts[ 1 ] ) ) {
				$this->setAction( 'index' );
			} else {
				$this->setAction( $request_uri_parts[ 1 ] );
			}

			if ( ! empty( $request_uri_parts[ 2 ] ) ) {
				$this->setIdMatched( $request_uri_parts[ 2 ] );
			}
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::getEnvironment()
	 */
	public function getEnvironment ( ): string {
		if ( ! $this->environment ) {
			$this->environment = getenv( 'APPLICATION_ENV' );
		}

		return $this->environment;
	}

	/**
	 *
	 * @return WhiteLabel
	 */
	public function getWhiteLabel ( ): WhiteLabel {
		$white_label_list = new WhiteLabelList();
		$white_label_list->findAll();
		return $white_label_list->offsetGet( 0 );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::getSkin()
	 */
	public function getSkin ( ): ISkin {
		if ( $this->skin == null ) {
			$this->skin = $this->getSkinDomain()->getSkin();
		}
		return $this->skin;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::getSkinDomain()
	 */
	public function getSkinDomain ( ): ISkinDomain {
		if ( ! $this->skin_domain ) {

			$domain = $_SERVER[ 'HTTP_HOST' ];

			$skin_domain_list = new SkinDomainList();

			$skin_domain_list->where( [ 
					'domain' => $domain
			] )->orWhere( [ 
					'api_domain' => $domain
			] );

			$this->skin_domain = $skin_domain_list->offsetGet( 0 );

			if ( ! $this->skin_domain ) {
				throw new Exception( "Unable to determine skin domain with domain {$domain}. " );
			}
		}

		return $this->skin_domain;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::isProduction()
	 */
	public function isProduction ( ): bool {
		return getenv( 'APPLICATION_ENV' ) === IContext::ENVIRONMENT_PRODUCTION;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::isMobile()
	 */
	public function isMobile ( ): bool {
	}

	/**
	 *
	 * @param string $controller
	 */
	private function setController ( string $controller ) {
		$this->controller = $controller;
	}

	/**
	 *
	 * @return string|NULL
	 */
	public function getController ( ): ?string {
		return $this->controller;
	}

	/**
	 *
	 * @deprecated
	 * @return string|NULL
	 */
	public function getControllerName ( ): ?string {
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
	 * @return string|NULL
	 */
	public function getAction ( ): ?string {
		return $this->action;
	}

	/**
	 *
	 * @param string $id_matched
	 */
	private function setIdMatched ( string $id_matched ) {
		$this->id_matched = $id_matched;
	}

	/**
	 * Gets the id match in the URL path of the request
	 *
	 * @return string|NULL The id matched in the URL of the request, null if it was not matched
	 */
	public function getIdMatched ( ): ?string {
		return $this->id_matched;
	}
}
