<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class OrderByClause extends Clause implements IOrderByClause {
	
	/**
	 *
	 * @var \ArrayObject
	 */
	private $orders;
	
	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		parent::__construct( $statement );
	}
	
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\IOrderByClause::orderBy()
	 */
	public function orderBy ( $expression, $order = IOrderByClause::ORDER_ASC) {
		$this->orders = new \ArrayObject();
		
		$this->orders->append( [ 
				$expression,
				$order
		] );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\IOrderByClause::addOrderBy()
	 */
	public function addOrderBy ( $expression, $order = IOrderByClause::ORDER_ASC) {
		$this->orders->append( [ 
				$expression,
				$order
		] );
	}
	
	public function __toString ( ) {
		$orders = [ ];
		
		if ( ! $this->orders || ! ($this->orders->count()) ) {
			return $orders;
		}
		
		foreach ( $this->orders as $o ) {
			$orders[] = $o[ 0 ] . ' ' . $o[ 1 ];
		}
		
		$orders = implode( " , ", $orders );
		
		return "\nORDER BY " . $orders;
	}
}