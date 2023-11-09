<?php

namespace Wolfgang\Interfaces\Model;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface ISkinDomain extends IModel {

	/**
	 *
	 * @return ISkin
	 */
	public function getSkin ( ): ISkin;
}
