<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\ORM\ITable;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IUpdateClause extends IClause {
	
	/**
	 *
	 * @return ITable
	 */
	public function getTableReference ( ): ITable;
}