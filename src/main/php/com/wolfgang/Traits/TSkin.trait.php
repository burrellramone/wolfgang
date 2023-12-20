<?php

namespace Wolfgang\Traits;

use Wolfgang\Interfaces\ISkinDomain;
/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TSkin {
	/**
	 *
	 * @var string
	 */
	private $name;

	/***
	 * @var ISkinDomain
	 */
	private $skin_domain;
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string {
		return $this->name;
	}

	public function getSkinDomain(): ISkinDomain {
		return $this->skin_domain;
	}
}
