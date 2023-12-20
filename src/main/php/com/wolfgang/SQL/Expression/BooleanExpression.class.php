<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Exceptions\IllegalStateException;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class BooleanExpression extends NumericExpression {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Expression\NumericExpression::init()
	 */
	protected function init ( ) {
		parent::init();
		
		if ( ($this->expression !== false) && ($this->expression !== true) && ($this->expression !== null) ) {
			throw new IllegalStateException( "Expression should be that of boolean true or false" );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Expression\NumericExpression::__toString()
	 */
	public function __toString ( ) {
		if ( $this->expression === true ) {
			return 'TRUE';
		} else if ( $this->expression === false ) {
			return 'FALSE';
		} else if ( $this->expression === null ) {
			return 'NULL';
		} else {
			return 'NULL';
		}
	}
}
