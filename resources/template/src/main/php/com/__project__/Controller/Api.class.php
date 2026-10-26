<?php

namespace Controller;

use Wolfgang\Controller\ApiController;

//
use Application\Api as ApiApplication;
use Traits\TController;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
abstract class Api extends ApiController {
	use TController;

	/**
	 * 
	 */
	protected function getApplication():ApiApplication {
		if(!$this->application){
			$this->application = ApiApplication::getInstance();
		}

		return $this->application;
	}
}