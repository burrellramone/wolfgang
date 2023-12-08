<?php

namespace Wolfgang\SQL\Statement\DML;

use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;
use Wolfgang\SQL\Expression\Expression;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Interfaces\SQL\Clause\ILimitClause;
use Wolfgang\SQL\Clause\WhereClause;
use Wolfgang\SQL\Clause\LimitClause;
use Wolfgang\SQL\Clause\UpdateClause;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\SQL\Expression\ConditionalExpressionGroup;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class UpdateStatement extends Statement implements IUpdateStatement {
	
	/**
	 *
	 * @var IUpdateClause
	 */
	protected $update_clause;
	
	/**
	 *
	 * @var IWhereClause
	 */
	protected $where_clause;
	
	/**
	 *
	 * @var ILimitClause
	 */
	protected $limit_clause;
	
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $bound_columns;
	
	/**
	 *
	 * @param ITable $table
	 */
	public function __construct ( ITable $table ) {
		parent::__construct( $table );
		
		$this->update_clause = new UpdateClause( $this, $table );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Statement\DML\Statement::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->where_clause = new WhereClause( $this );
		$this->limit_clause = new LimitClause( $this );
		$this->bound_columns = new \ArrayObject();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::getUpdateClause()
	 */
	public function getUpdateClause ( ): IUpdateClause {
		return $this->update_clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::getWhereClause()
	 */
	public function getWhereClause ( ): IWhereClause {
		return $this->where_clause;
	}
	
	/**
	 *
	 * @param array|callable $where
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 */
	private function _where ( $where, $logical_operator = IConditionalExpression::LOGICAL_OPERATOR_AND) {
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
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::where()
	 */
	public function where ( $where ) {
		$this->where_clause = new WhereClause( $this );
		
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_AND );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::andWhere()
	 */
	public function andWhere ( $where ) {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_AND );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::orWhere()
	 */
	public function orWhere ( $where ) {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_OR );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::bind()
	 */
	public function bind ( $column, $value, $encrypt = false) {
		if ( ! $column ) {
			throw new IllegalArgumentException( "Column name must be provided" );
		} else if ( ! is_string( $column ) ) {
			throw new InvalidArgumentException( "Column name must be a string" );
		} else if ( ! $this->getUpdateClause()->getTableReference()->isColumn( $column ) ) {
			throw new InvalidArgumentException( "Unknown column name '{$column}' of table {$this->getUpdateClause()->getTableReference()->getName()}" );
		}
		
		$this->bound_columns->append( [ 
				'column' => $column,
				'expression' => Expression::create( $this->getUpdateClause(), $value ),
				'encrypt' => $encrypt
		] );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::limit()
	 */
	public function limit ( $limit ) {
		$this->limit_clause = new LimitClause( $this );
		
		$this->limit_clause->setLimit( $limit );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement::getLimitClause()
	 */
	public function getLimitClause ( ): ILimitClause {
		return $this->limit_clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		if ( ! $this->getWhereClause()->getExpressions()->count() ) {
			throw new IllegalStateException( "Cannot update table without where clause conditional expressions." );
		}
		
		$encryption_key = $this->getUpdateClause()->getTableReference()->getSchema()->getDsn()->getEncryptionKey();
		
		$statement = ( string ) $this->getUpdateClause();
		$statement .= ' SET ';
		
		$values = [ ];
		
		foreach ( $this->bound_columns as $bound_column ) {
			$column = $bound_column[ 'column' ];
			$encrypt = $bound_column[ 'encrypt' ];
			$value = ( string ) $bound_column[ 'expression' ];
			
			if ( $encrypt ) {
				$values[] = "`{$column}` = AES_ENCRYPT( $value, '{$encryption_key}')";
			} else {
				$values[] = "`{$column}` = $value";
			}
		}
		
		$statement = $statement . implode( ',', $values );
		
		$statement .= ( string ) $this->getWhereClause();
		
		$statement .= ( string ) $this->getLimitClause();
		
		return $statement;
	}
}