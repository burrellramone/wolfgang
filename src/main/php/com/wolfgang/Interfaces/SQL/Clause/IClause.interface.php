<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IClause {
	
	/**
	 *
	 * @return IStatement
	 */
	public function getStatement ( ): IStatement;
}