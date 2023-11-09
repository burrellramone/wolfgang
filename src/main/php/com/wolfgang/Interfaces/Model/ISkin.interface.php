<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @package Wolfgang\Interfaces\Model
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface ISkin extends IModel {
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;
}
