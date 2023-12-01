<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @package Wolfgang\Interfaces\Model
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface IBridgeModel extends IModel {
	/**
	 *
	 * @return array
	 */
	public function getBridgeColumnNames ( ): array;
}
