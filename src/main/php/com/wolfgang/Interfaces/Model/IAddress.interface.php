<?php

namespace Wolfgang\Interfaces\Model;

use Wolfgang\Util\LatLng;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IAddress extends IModel {

	/**
	 *
	 * @return LatLng
	 */
	public function getLatLng ( ): LatLng;
}
