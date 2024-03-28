<?php

namespace Wolfgang\Interfaces\Model;
use Wolfgang\Interfaces\SQL\IUnduplicable;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IBridgeModel extends IUnduplicable, IModel {
	/**
	 *
	 * @return array
	 */
	public function getBridgeColumnNames ( ): array;
}
