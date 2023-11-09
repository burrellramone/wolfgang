<?php

namespace Wolfgang\Message\CLI;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Traits\TSingleton;
use Wolfgang\Traits\Message\TResponse;

/**
 *
 * @package Wolfgang\Message\CLI
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Response extends Message implements ISingleton , IResponse {
	use TSingleton;
	use TResponse;
}
