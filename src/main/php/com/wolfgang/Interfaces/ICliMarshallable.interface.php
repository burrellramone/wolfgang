<?php

namespace Wolfgang\Interfaces;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
interface ICliMarshallable {
	/**
	 *
	 * @return void
	 */
	public function climarshall ( ): void;

	/**
     * @return array
     */
    public function getPrintableFields():array;
}