<?php

namespace Wolfgang\Interfaces\SQL\Expression;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IConditionalExpressionGroup extends IConditionalExpression {

	/**
	 */
	public function or ( );

	/**
	 */
	public function and ( );
}