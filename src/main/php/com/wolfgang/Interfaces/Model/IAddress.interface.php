<?php

namespace Wolfgang\Interfaces\Model;

use Wolfgang\Util\LatLng;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface IAddress extends IModel {

	/**
	 *
	 * @return LatLng
	 */
	public function getLatLng ( ): LatLng;
}
