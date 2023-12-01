<?php

namespace Wolfgang\Traits;

use Wolfgang\Interfaces\ISkinDomain;
/**
 *
 * @package Wolfgang\Traits
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
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
