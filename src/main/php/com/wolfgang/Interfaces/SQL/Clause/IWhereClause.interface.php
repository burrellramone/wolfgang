<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IWhereClause extends IClause {
	const LOGICAL_OPERATOR_AND = 'AND';
	const LOGICAL_OPERATOR_OR = 'OR';

	public function addExpression ( IConditionalExpression $expression );
	public function getExpressions ( ): \ArrayObject;
}
