<?php

namespace Wolfgang\Interfaces\SQL\Expression;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IConditionalExpressionGroup extends IConditionalExpression {

	/**
	 */
	public function or ( );

	/**
	 */
	public function and ( );
}