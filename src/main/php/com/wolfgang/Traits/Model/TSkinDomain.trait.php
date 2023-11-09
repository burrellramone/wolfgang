<?php

namespace Wolfgang\Traits\Model;

use Wolfgang\Interfaces\Model\ISkin;
use Wolfgang\Model\Skin;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Traits\Model
 * @since Version 1.0.0
 */
trait TSkinDomain {
	/**
	 *
	 * @var ISkin
	 */
	protected $skin;

	/**
	 *
	 * @return ISkin
	 */
	public function getSkin ( ): ISkin {
		if ( ! $this->skin ) {
			$this->skin = Skin::findById( $this->skin_id );
		}
		return $this->skin;
	}
}
