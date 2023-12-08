<?php

namespace Wolfgang\SQL\Statement\DML;

use Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\ILimitClause;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;
use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;
use Wolfgang\SQL\Clause\DeleteClause;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\SQL\Clause\WhereClause;
use Wolfgang\SQL\Clause\LimitClause;
use Wolfgang\SQL\Clause\FromClause;
use Wolfgang\SQL\Clause\OrderByClause;
use Wolfgang\SQL\Expression\ConditionalExpressionGroup;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class DeleteStatement extends Statement implements IDeleteStatement {
	
	/**
	 *
	 * @var IDeleteClause
	 */
	protected $delete_clause;
	
	/**
	 *
	 * @var IFromClause
	 */
	protected $from_clause;
	
	/**
	 *
	 * @var IWhereClause
	 */
	protected $where_clause;
	
	/**
	 *
	 * @var IOrderByClause
	 */
	protected $order_by_clause;
	
	/**
	 *
	 * @var ILimitClause
	 */
	protected $limit_clause;
	
	/**
	 *
	 * @param ITable $table
	 */
	public function __construct ( ITable $table ) {
		$this->from_clause = new FromClause( $this, $table );
		
		parent::__construct( $table );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Statement\DML\Statement::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->delete_clause = new DeleteClause( $this );
		$this->where_clause = new WhereClause( $this );
		$this->limit_clause = new LimitClause( $this );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::lowPriority()
	 */
	public function lowPriority ( ) {
		$this->off( IDeleteStatement::MODIFIER_QUICK );
		$this->off( IDeleteStatement::MODIFIER_IGNORE );
		$this->on( IDeleteStatement::MODIFIER_LOW_PRIORITY );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::quick()
	 */
	public function quick ( ) {
		$this->off( IDeleteStatement::MODIFIER_IGNORE );
		$this->off( IDeleteStatement::MODIFIER_LOW_PRIORITY );
		$this->on( IDeleteStatement::MODIFIER_QUICK );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::ignore()
	 */
	public function ignore ( ) {
		$this->off( IDeleteStatement::MODIFIER_QUICK );
		$this->off( IDeleteStatement::MODIFIER_LOW_PRIORITY );
		$this->on( IDeleteStatement::MODIFIER_IGNORE );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::getDeleteClause()
	 */
	public function getDeleteClause ( ): IDeleteClause {
		return $this->delete_clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::getFromClause()
	 */
	public function getFromClause ( ): IFromClause {
		return $this->from_clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::getWhereClause()
	 */
	public function getWhereClause ( ): IWhereClause {
		return $this->where_clause;
	}
	
	/**
	 *
	 * @param array|callable $where
	 * @param string $logical_operator
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 */
	private function _where ( $where, $logical_operator = IConditionalExpression::LOGICAL_OPERATOR_AND ) {
		if ( empty( $where ) ) {
			throw new IllegalArgumentException( "Where condition not provided" );
		} else if ( ! is_array( $where ) && ! is_callable( $where ) ) {
			throw new InvalidArgumentException( "Invalid argument provided for where condition" );
		}
		
		if ( is_array( $where ) ) {
			foreach ( $where as $subject => $condition ) {
				$expression = new ConditionalExpressionGroup( $this->getWhereClause(), $logical_operator );
				
				if ( is_array( $condition ) ) {
					$expression->in( $subject, $condition );
				} else {
					$expression->eq( $subject, $condition );
				}
				
				$this->getWhereClause()->addExpression( $expression );
			}
		} else {
			$expression = new ConditionalExpressionGroup( $this->getWhereClause(), $logical_operator );
			$expression = $where( $expression );
			$this->getWhereClause()->addExpression( $expression );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::where()
	 */
	public function where ( $where ) {
		$this->where_clause = new WhereClause( $this );
		
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_AND );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::andWhere()
	 */
	public function andWhere ( $where ) {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_AND );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::orWhere()
	 */
	public function orWhere ( $where ) {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_OR );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::orderBy()
	 */
	public function orderBy ( $field, $order = IOrderByClause::ORDER_ASC ) {
		$this->order_by_clause = new OrderByClause( $this );
		
		$this->order_by_clause->orderBy( $field, $order );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::addOrderBy()
	 */
	public function addOrderBy ( $field, $order = IOrderByClause::ORDER_ASC ) {
		$this->order_by_clause->addOrderBy( $field, $order );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::getOrderByClause()
	 */
	public function getOrderByClause ( ): IOrderByClause {
		return $this->order_by_clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::limit()
	 */
	public function limit ( $limit ) {
		$this->limit_clause->setLimit( $limit );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement::getLimitClause()
	 */
	public function getLimitClause ( ): ILimitClause {
		return $this->limit_clause;
	}
	
	public function __toString ( ) {
		if ( ! $this->getWhereClause()->getExpressions()->count() ) {
			throw new IllegalStateException( "Cannot delete from table without where clause conditional expressions." );
		}
		
		$statement = ( string ) $this->getDeleteClause();
		
		$statement .= ( string ) $this->getFromClause();
		
		$statement .= ( string ) $this->getWhereClause();
		
		$statement .= ( string ) $this->getLimitClause();
		
		return $statement;
	}
}
