<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IMarshaller {
	
	/**
	 *
	 * @param IMarshallable $object
	 * @return \stdClass
	 */
	public function marshall ( IMarshallable $object ): \stdClass;
}