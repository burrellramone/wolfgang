<?php

namespace Wolfgang\Interfaces;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IMarshaller {
	
	/**
	 *
	 * @param IMarshallable $object
	 * @return \stdClass
	 */
	public function marshall ( IMarshallable $object ): \stdClass;
}