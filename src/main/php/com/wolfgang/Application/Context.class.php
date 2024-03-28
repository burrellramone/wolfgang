<?php

namespace Wolfgang\Application;

//Wolfgang
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\ISkin;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Network\Uri\Uri;
use Wolfgang\Skin;

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
	 * @var string
	 */
	private $application;

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
	 * @var array
	 */
	private $cli_options = array();

	/**
	 * @var array
	 */
	private $sites = array();

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

			$this->setApplication($this->getSkin()->getName());

			//Redirect rules will sometimes set controller and and action in query string
			if(isset($_GET['c'], $_GET['a'])) {
				$this->setController( $_GET['c'] );
				$this->setAction( $_GET['a'] );

			} else {
				if ( empty( $request_uri_parts[ 0 ] ) ) {
					$this->setController( 'index' );
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
		} else if ( PHP_SAPI ==  IContext::PHP_SAPI_CLI) {
			$shortopts = "A:c:a:";
			$longopts = [
					"application:",
					"controller:",
					"action:",
					"id:",
					"firstname:",
					"lastname:",
					"email:",
					"phone:",
					"user_phone:",
					"user_email:",
					"password:",
					"url:",
					"status_id:",
					"timezone_id:",
					"type_id:",
					"bucket:",
					"stripe-live-secret-key:"
			];

			$options = getopt( $shortopts, $longopts );

			if($options){
				$this->cli_options = $options;

				if(isset($this->cli_options['A'])){
					$this->setApplication($this->cli_options['A']);
				} else if (isset($this->cli_options['application'])) {
					$this->setApplication($this->cli_options['application']);
				} else {
					throw new IllegalArgumentException("Application not provided");
				}

				if(isset($this->cli_options['c'])){
					$this->setController($this->cli_options['c']);
				} else if (isset($this->cli_options['controller'])) {
					$this->setController($this->cli_options['controller']);
				} else {
					throw new IllegalArgumentException("Controller not provided");
				}

				if(isset($this->cli_options['a'])){
					$this->setAction($this->cli_options['a']);
				} else if (isset($this->cli_options['action'])) {
					$this->setAction($this->cli_options['action']);
				} else {
					throw new IllegalArgumentException("Action not provided");
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
			$skins = include DOCUMENT_ROOT . 'sites.php';

			if ( PHP_SAPI == IContext::PHP_SAPI_CLI ) {
				$this->skin = new Skin($skins['cli']);
			} else {
				$domain = strtok($_SERVER['HTTP_HOST'], ':');

				foreach($skins as $key => $skin){
					if($key == 'cli'){
						continue;
					}
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

	/**
	 * @param int $id
	 * @return Skin
	 */
	public function getSkinById(int $id):Skin{
		if(isset($this->skins[$id])) {
			return $this->skins[$id];
		}

		if(empty($this->sites)){
			$this->sites = include DOCUMENT_ROOT . 'sites.php';
		}
		
		foreach($this->sites as $skin){
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
	 * @param string $application
	 */
	private function setApplication(string $application) {
		$this->application = $application;
	}

	public function getApplication():string {
		return $this->application;
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

	/**
	 * @return array
	 */
	public function getCliOptions():array {
		return $this->cli_options;
	}

	/**
	 * @return bool
	 */
	public function isCli(): bool {
		return  PHP_SAPI ==  IContext::PHP_SAPI_CLI;
	}

	/**
	 * @return bool
	 */
	public function isCron(): bool {
		if(!$this->isCli()){
			return false;
		}

		if( isset( $_SERVER['TERM'] ) ) {
			return false;
		}

		return true;
	}
}