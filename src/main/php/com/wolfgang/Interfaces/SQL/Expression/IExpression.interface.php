<?php

namespace Wolfgang\Interfaces\SQL\Expression;

use Wolfgang\Interfaces\SQL\Clause\IClause;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IExpression {
	
	const TYPE_CASE_EXPRESSION = 1;
	const TYPE_COLUMN_EXPRESSION = 2;
	const TYPE_CONDITIONAL_EXPRESSION = 3;
	const TYPE_DATETIME_EXPRESSION = 4;
	const TYPE_FUNCTION_EXPRESSION = 5;
	const TYPE_INTERVAL_EXPRESSION = 6;
	const TYPE_NUMERIC_EXPRESSION = 6;
	const TYPE_STRING_EXPRESSION = 7;
	
	/**
	 * Gets the instance of the clause this expression is contained in
	 *
	 * @return IClause
	 */
	public function getClause ( ): IClause;
}