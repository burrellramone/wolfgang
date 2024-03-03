<?php

namespace Wolfgang\Interfaces;

use \stdClass;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IMarshaller {
	
	/**
	 *
	 * @param IMarshallable $object
	 * @return stdClass|array
	 */
	public function marshall ( IMarshallable $object ): stdClass|array;
}