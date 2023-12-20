<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface ISkinDomain {

	public function getDomain():string;
	public function getApiDomain():string;
	public function getUrl():string;
	public function getApiUrl():string;
}
