<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Date\DateTime;
use Wolfgang\Interfaces\SQL\Clause\IClause;
use Wolfgang\Application\Application;

/**
 *
 * @package Wolfgang\SQL\Expression
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
class NumericExpression extends Expression {
	
	/**
	 *
	 * @param IClause $clause
	 * @param string|int|array|double|DateTime $expression
	 */
	public function __construct ( IClause $clause, $expression ) {
		parent::__construct( $clause, $expression );
	}
	
	/**
	 *
	 * @return string
	 */
	public function __toString ( ) {
		try {
			return ( string ) $this->expression;
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}
	}
}