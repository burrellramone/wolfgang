<?php

namespace Wolfgang;

use Wolfgang\Interfaces\ISkin;
use Wolfgang\Traits\TSkin;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Skin extends Component implements ISkin {
	use TSkin;

	private $id;

	/**
	 * @var SkinDomain|null
	 */
	private $skin_domain;
	
	private array $routes = [];

	public function __construct(array $definition) {
		parent::__construct();

		if( empty($definition) ) {
			throw new InvalidArgumentException("Skin definition is empty");
		}

		$this->id = $definition['id'];
		$this->name = $definition['name'];

		if(!empty($definition['skin_domain'])){
			$this->skin_domain = new SkinDomain($definition['skin_domain']);
		}
		
		if (isset($definition['routes'])) {
		    $this->routes = $definition['routes'];
		}
	}

	public function getId():string|int {
		return $this->id;
	}
	
	/**
	 * Gets the defined routes for this site
	 * 
	 * @return array
	 */
	public function getRoutes():array {
	    return $this->routes;
	}

	/**
	 * 
	 * @return array
	 */
	public function getDomainRoutes():array {
		return $this->getRoutes()['domain'] ?? [];
	}

	/**
	 *
	 * @return array
	 */
	public function getApiDomainRoutes():array {
		return $this->getRoutes()['api_domain'] ?? [];
	}
	
	public function isCli():bool {
	    return $this->name == 'CLI';
	}
}
