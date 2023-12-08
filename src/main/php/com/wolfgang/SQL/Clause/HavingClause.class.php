<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\SQL\Clause\IHavingClause;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class HavingClause extends Clause implements IHavingClause {
	
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $expressions;
	
	protected function init ( ) {
		parent::init();
		
		$this->expressions = new \ArrayObject();
	}
	
	/**
	 *
	 * @param IConditionalExpression $expression
	 */
	public function addExpression ( IConditionalExpression $expression ) {
		$this->expressions->append( $expression );
	}
	
	/**
	 *
	 * @return \ArrayObject
	 */
	public function getExpressions ( ): \ArrayObject {
		return $this->expressions;
	}
	
	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		parent::__construct( $statement );
	}
	
	public function __toString ( ) {
		if ( ! $this->getExpressions()->count() ) {
			return '';
		}
		
		$where_clause = "\HAVING \n";
		
		foreach ( $this->expressions as $expression ) {
			$where_clause .= " \n" . $expression->getLogicalOperator() . ' ' . $expression;
		}
		
		return preg_replace( "/HAVING([\s]{1,})AND/i", "HAVING ", $where_clause );
	}
}