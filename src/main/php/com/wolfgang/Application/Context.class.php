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
use Wolfgang\Exceptions\InvalidStateException;

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
	 * @var Uri
	 */
	
	private Uri $request_uri;
	
	/**
	 * 
	 * @var string
	 */
	private string $domain;

	/**
	 *
	 * @var ISkin
	 */
	private $skin;


	/**
	 * @var string
	 */
	private $application;
	
	/**
	 * 
	 * @var string
	 */
	private $version;

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
	 * @var int|null
	 */
	private int|null $cli_skin_id = null;
	
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
        
		if (!defined('SITES_FILE_PATH')) {
		    throw new Exception("'SITES_FILE_PATH' constant not defined.");
		}
		
		$sitesFilepath = SITES_FILE_PATH;
		$sites = include $sitesFilepath;
		
		if (!is_array($sites)) {
		    throw new InvalidStateException("Sites file '{$sitesFilepath}' does not return an array.");
		}
		
		foreach($sites as $site){
	        $this->sites[$site['id']] = new Skin($site);
	        if (strtolower($site['name']) == 'cli') {
	            $this->cli_skin_id = $site['id'];
	        }
		}
		
		
		if ( PHP_SAPI == IContext::PHP_SAPI_APACHE2_HANDLER ) {
		    $domain = strtok($_SERVER['HTTP_HOST'], ':');
			$uri = new Uri( isset( $_SERVER[ 'REDIRECT_URL' ] ) ? $_SERVER[ 'REDIRECT_URL' ] : $_SERVER[ 'REQUEST_URI' ] );
			
			$this->setRequestUri($uri);
			$this->setDomain($domain);
			
			$request_uri_parts = array_values( array_filter( explode( '/', $uri->getPath() ) ) );
			$versions = ["v1", "v2", "v3", "v4", "v5"];
			
			$versionIdx = array_find_key($versions, function($version) use($request_uri_parts){
			    return in_array($version, $request_uri_parts);
			});
		
			if ($versionIdx !== null) {
			    $version = $request_uri_parts[$versionIdx];
			    unset($request_uri_parts[$versionIdx]);
			    $request_uri_parts = array_values($request_uri_parts);
			    $this->setVersion($version);
			}

			$site = $this->getSite();
			$this->setApplication($site->getName());
			

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
	 * @deprecated
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IContext::getSkin()
	 */
	public function getSkin ( ): ISkin {
		return $this->getSite();
	}
	
	public function getSite():ISkin {
	    if ( $this->skin == null ) {
	        if ( PHP_SAPI == IContext::PHP_SAPI_CLI ) {
	            $this->skin = $this->getSkinById($this->getCliSkinId());
	        } else {
	            $domain = $this->getDomain();
	            
	            foreach($this->sites as $site){
	                if($site->isCli()){
	                    continue;
	                }
	                
	                if($site->getSkinDomain()->getDomain() == $domain || $site->getSkinDomain()->getApiDomain() == $domain) {
	                    $this->skin = $site;
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
	 * Sets the domain for this context
	 * 
	 * @param string $domain
	 */
	private function setDomain(string $domain):void {
	    $this->domain = $domain;
	}
	
	/**
	 * Gets the domain for this context
	 * @return string
	 */
	public function getDomain():string {
	    return $this->domain;
	}
	
	/**
	 * Sets the requst URI for this context
	 * 
	 * @param Uri $uri
	 */
	private function setRequestUri(Uri $uri):void {
	    $this->request_uri = $uri;
	}
	
	/**
	 * Gets the request URI for this context
	 * 
	 * @return Uri
	 */
	public function getRequestUri():Uri {
	    return $this->request_uri;
	}
	
	/**
	 * Gets the id of the CLI site
	 * 
	 * @return int|null
	 */
	private function getCliSkinId():int|null {
	    return $this->cli_skin_id;
	}

	/**
	 * @param int $id
	 * @throws InvalidStateException
	 * @return Skin
	 */
	public function getSkinById(int $id):Skin{
		if(isset($this->sites[$id])) {
		    return $this->sites[$id];
		}

        throw new InvalidStateException("Site with id '{$id}' not found");	
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
	 * Sets the version of the request / controller call
	 * 
	 * @param string $version
	 * @return void
	 */
	private function setVersion(string $version):void {
	    $this->version = strtoupper($version);
	}
	
	/**
	 * Gets the version of the request / controller call
	 * 
	 * @return string|null
	 */
	public function getVersion():?string {
	    return $this->version;
	}
	
	/**
	 *
	 * @param string $controller
	 */
	private function setController ( string $controller ) {
		$this->controller = strtolower($controller);
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