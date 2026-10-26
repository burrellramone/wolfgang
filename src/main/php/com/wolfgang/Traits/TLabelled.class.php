<?php

namespace Wolfgang\Traits;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
trait TLabelled {
	/**
	 *
	 * @var string
	 */
	protected $label;
	
	/**
	 *
	 * @var string
	 */
	protected $description;
	
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
