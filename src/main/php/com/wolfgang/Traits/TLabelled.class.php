<?php

namespace Wolfgang\Traits;

/**
 *
 * @package Wolfgang\Traits
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
trait TLabelled {
	/**
	 *
	 * @var string
	 */
	public $label;
	
	/**
	 *
	 * @var string
	 */
	public $description;
	
	/**
	 *
	 * @return string
	 */
	public function getLabel ( ): string {
		return $this->label;
	}
	
	/**
	 *
	 * @return string|NULL
	 */
	public function getDescription ( ): ?string {
		return $this->description;
	}
}
