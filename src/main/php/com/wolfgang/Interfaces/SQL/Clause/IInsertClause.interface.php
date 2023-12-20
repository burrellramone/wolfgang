<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\ORM\ITable;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IInsertClause extends IClause {
	
	/**
	 *
	 * @return ITable
	 */
	public function getTableReference ( ): ITable;
}