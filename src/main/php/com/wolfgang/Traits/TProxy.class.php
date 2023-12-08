<?php

namespace Wolfgang\Traits;

use Wolfgang\Component as BaseComponent;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
trait TProxy {
	
	/**
	 *
	 * @var BaseComponent
	 */
	protected $subject;
	
	/**
	 *
	 * @param BaseComponent $subject
	 */
	protected function setSubject ( BaseComponent $subject ) {
		$this->subject = $subject;
	}
	
	/**
	 *
	 * @return BaseComponent
	 */
	public function getSubject ( ): BaseComponent {
		if ( ! $this->subject ) {
			$this->load();
		}
		
		return $this->subject;
	}
	
	abstract protected function load ( );
}
