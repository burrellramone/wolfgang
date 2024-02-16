<?php

namespace Wolfgang\Traits;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
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
