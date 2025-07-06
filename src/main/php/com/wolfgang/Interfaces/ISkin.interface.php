<?php
namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.0.1
 */
interface ISkin {
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;

	/**
	 * 
	 * @return void
	 */
	public function getSkinDomain(): ISkinDomain;
}
