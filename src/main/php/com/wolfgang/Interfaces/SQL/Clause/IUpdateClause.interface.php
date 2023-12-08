<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\ORM\ITable;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IUpdateClause extends IClause {
	
	/**
	 *
	 * @return ITable
	 */
	public function getTableReference ( ): ITable;
}