<?php

namespace Wolfgang\Interfaces\ORM;

/**
 *
 * @package Wolfgang\Interfaces\ORM
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IMySQLiColumn extends IColumn {
	/**
	 *
	 * @return int
	 */
	public function getFlags ( ): int;
}
