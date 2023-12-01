<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IWhereClause extends IClause {
	const LOGICAL_OPERATOR_AND = 'AND';
	const LOGICAL_OPERATOR_OR = 'OR';

	public function addExpression ( IConditionalExpression $expression );
	public function getExpressions ( ): \ArrayObject;
}
