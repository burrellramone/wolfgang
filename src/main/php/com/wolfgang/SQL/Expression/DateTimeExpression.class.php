<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Date\DateTime;
use Wolfgang\Application\Application;

/**
 *
 * @package Wolfgang\SQL\Expression
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class DateTimeExpression extends Expression {
	
	public function __toString ( ) {
		$expression = null;
		
		try {
			
			if ( ($this->expression instanceof DateTime) ) {
				$expression = "'" . ( string ) $this->expression . "'";
			} else if ( in_array( $this->expression, [ 
					'CURRENT_TIMESTAMP'
			] ) ) {
				$expression = $this->expression;
			}
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}
		
		return $expression;
	}
}
