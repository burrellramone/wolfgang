<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IClause {
	
	/**
	 *
	 * @return IStatement
	 */
	public function getStatement ( ): IStatement;
}