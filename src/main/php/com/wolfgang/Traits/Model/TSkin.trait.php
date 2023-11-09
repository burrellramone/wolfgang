<?php

namespace Wolfgang\Traits\Model;

/**
 *
 * @package Component\Traits\Model
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
trait TSkin {
	/**
	 *
	 * @var string
	 */
	public $name;
	
	/**
	 *
	 * @var string
	 */
	public $abbreviated_name;
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string {
		return $this->name;
	}
}
