<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IMarshallable {
	/**
	 *
	 * @return mixed
	 */
	public function marshall ( ): array;
}
