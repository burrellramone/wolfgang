<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Wolfgang\Interfaces
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.com
 * @since Version 1.0.0
 */
interface ISkinDomain {

	public function getDomain():string;
	public function getApiDomain():string;
	public function getUrl():string;
	public function getApiUrl():string;
}
