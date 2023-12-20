<?php

namespace Wolfgang\Application;

//Wolfgang
use Wolfgang\StringObject;
use Wolfgang\Skin;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\ISkin;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Network\Uri\Uri;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Interfaces\ISingleton
 * @since Version 0.1.0
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
	 * @var ISkin
	 */
	private $skin;

	/**
	 * @var array
	 */
	private $skins = [];

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

			//Redirect rules will sometimes set controller and and action in query string
			if(isset($_GET['c'], $_GET['a'])) {
				$this->setController( $_GET['c'] );
				$this->setAction( $_GET['a'] );

			} else {
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
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::getSkin()
	 */
	public function getSkin ( ): ISkin {
		if ( $this->skin == null ) {
			if ( PHP_SAPI == IContext::PHP_SAPI_CLI ) {
				$this->skin = new Skin(array(
					'name' => IContext::PHP_SAPI_CLI,
					'skin_domain' => array(
					)
				));
			
			} else {
				$domain = $_SERVER[ 'HTTP_HOST' ];

				$skins = include DOCUMENT_ROOT . 'sites.php';

				foreach($skins as $skin){
					if($skin['skin_domain']['domain'] == $domain || $skin['skin_domain']['api_domain'] == $domain) {
						$this->skin = new Skin($skin);
					}
				}
				
				if(!$this->skin) {
					throw new Exception( "Unable to determine skin with domain {$domain}. " );
				}
			}
		}

		return $this->skin;
	}

	public function getSkinById(int $id) {
		if(isset($this->skins[$id])) {
			return $this->skins[$id];
		}

		$skins = include DOCUMENT_ROOT . 'sites.php';

		foreach($skins as $skin){
			if($skin['id'] == $id) {
				$this->skins[$id] = new Skin($skin);
			}
		}

		return $this->skins[$id];
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
		return false;
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
