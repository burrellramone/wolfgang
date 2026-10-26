<?php

namespace Wolfgang\Interfaces\Routing;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface IHttpRoute extends IRoute {
    /**
	 *
	 * @return string
	 */
	public function getMethod ( ): string;
}