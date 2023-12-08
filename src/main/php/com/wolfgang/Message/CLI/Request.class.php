<?php

namespace Wolfgang\Message\CLI;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Traits\Message\TRequest;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Request extends Message implements ISingleton , IRequest {
	use TSingleton;
	use TRequest;
}
