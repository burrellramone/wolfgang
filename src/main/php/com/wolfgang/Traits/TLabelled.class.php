<?php

namespace Wolfgang\Traits;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
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
