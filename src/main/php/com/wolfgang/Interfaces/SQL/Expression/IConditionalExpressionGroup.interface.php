<?php

namespace Wolfgang\Interfaces\SQL\Expression;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
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