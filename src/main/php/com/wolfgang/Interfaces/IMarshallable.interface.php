<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @package Wolfgang\Interfaces
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IMarshallable {
	/**
	 *
	 * @return mixed
	 */
	public function marshall ( ): array;
}
