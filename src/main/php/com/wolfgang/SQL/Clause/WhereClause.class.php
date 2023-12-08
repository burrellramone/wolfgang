<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IDMLStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class WhereClause extends Clause implements IWhereClause {
	
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $expressions;
	
	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		if ( ! ($statement instanceof IDMLStatement) ) {
			throw new InvalidArgumentException( "Statement is not an instance of Wolfgang\Interfaces\SQL\Statement\IDMLStatement" );
		}
		
		parent::__construct( $statement );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Clause\Clause::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->expressions = new \ArrayObject();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\IWhereClause::addExpression()
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
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		if ( ! $this->expressions->count() ) {
			return '';
		}
		
		$where_clause = "\nWHERE \n";
		
		foreach ( $this->expressions as $expression ) {
			$where_clause .= " \n" . $expression->getLogicalOperator() . ' ( ' . $expression . ' ) ';
		}
		
		return preg_replace( "/WHERE([\s]{1,})AND/i", "WHERE ", $where_clause );
	}
}
