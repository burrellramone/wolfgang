<?php

namespace Wolfgang\Adapter;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Adapter
 * @since Version 1.0.0
 */
final class Manager extends Component implements ISingleton {
	use TSingleton;
}