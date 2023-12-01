<?php

namespace Wolfgang\Interfaces\Model;

use Wolfgang\Util\LatLng;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface IAddress extends IModel {

	/**
	 *
	 * @return LatLng
	 */
	public function getLatLng ( ): LatLng;
}
