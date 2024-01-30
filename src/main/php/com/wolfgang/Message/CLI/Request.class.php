<?php

namespace Wolfgang\Message\CLI;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Message\CLI\IRequest;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Traits\Message\TRequest;
use Wolfgang\Application\Context;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Request extends Message implements ISingleton , IRequest {
	use TSingleton;
	use TRequest;

	public function __construct(){
		parent::__construct();
	}

	public function init(){
		parent::init();

		$context = Context::getInstance();
		$options = $context->getCliOptions();
		
		foreach ( $options as $param => $value ) {
			$this->params->{$param} = $value;
		}
	}
}