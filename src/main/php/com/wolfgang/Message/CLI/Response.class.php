<?php

namespace Wolfgang\Message\CLI;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Traits\Message\TResponse;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Response extends Message implements ISingleton , IResponse {
	use TSingleton;
	use TResponse;
}
