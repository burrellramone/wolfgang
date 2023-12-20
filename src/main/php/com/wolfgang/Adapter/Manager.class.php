<?php

namespace Wolfgang\Adapter;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Manager extends Component implements ISingleton {
	use TSingleton;
}