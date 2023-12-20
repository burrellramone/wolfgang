<?php

namespace Wolfgang\Interfaces\ORM;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IMySQLiColumn extends IColumn {
	/**
	 *
	 * @return int
	 */
	public function getFlags ( ): int;
}
