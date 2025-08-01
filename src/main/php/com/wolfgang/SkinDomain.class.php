<?php

namespace Wolfgang;

use Wolfgang\Interfaces\ISkinDomain;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Traits\TSkinDomain;
use Wolfgang\Date\DateTime;
use Wolfgang\Config\Config;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class SkinDomain extends Component implements ISkinDomain {
	use TSkinDomain;

	/**
	 *
	 * @var string
	 */
	public $domain;

	/**
	 *
	 * @var string
	 */
	public $url;

	/**
	 *
	 * @var string
	 */
	public $api_domain;

	/**
	 *
	 * @var string
	 */
	public $api_url;

	/**
	 *
	 * @var boolean
	 */
	public $open;

	function __construct (array $definition) {
		parent::__construct();

		if(!empty($definition)) {
			foreach($definition as &$def){
				if(preg_match("/\{\{config\.(app.root_domain)\}\}/", $def, $matches)){
					$def = str_replace("{{config.app.root_domain}}", Config::get($matches[1]), $def);
				}
			}

			$this->domain = $definition['domain'];
			$this->api_domain = $definition['api_domain'];
			$this->url = $definition['url'];
			$this->api_url = $definition['api_url'];
			$this->open = $definition['open'];
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function isOpen ( ): bool {
		return $this->open;
	}

	/**
	 *
	 * @return string
	 */
	public function getDomain ( ): string {
		return $this->domain;
	}


	/**
	 *
	 * @return string
	 */
	public function getUrl ( ):string  {
		return $this->url;
	}

	/**
	 *
	 * @throws IllegalStateException::
	 * @return string
	 */
	public function getApiDomain ( ): string {
		if ( ! $this->api_domain ) {
			throw new IllegalStateException( "An API domain has not been defined for this skin domain" );
		}

		return $this->api_domain;
	}

	/**
	 *
	 * @throws IllegalStateException
	 * @return string
	 */
	public function getApiUrl ( ): string {
		if ( ! $this->api_url ) {
			throw new IllegalStateException( "An API URL has not been defined for this skin domain" );
		}
		return $this->api_url;
	}
}
