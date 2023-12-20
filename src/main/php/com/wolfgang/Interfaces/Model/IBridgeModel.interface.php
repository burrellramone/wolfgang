<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IBridgeModel extends IModel {
	/**
	 *
	 * @return array
	 */
	public function getBridgeColumnNames ( ): array;
}
