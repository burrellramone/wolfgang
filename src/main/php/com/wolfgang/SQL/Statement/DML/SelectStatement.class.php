<?php

namespace Wolfgang\SQL\Statement\DML;

use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Interfaces\SQL\Clause\IHavingClause;
use Wolfgang\Interfaces\SQL\Clause\IGroupByClause;
use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;
use Wolfgang\SQL\Expression\ConditionalExpression;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\ILimitClause;
use Wolfgang\SQL\Clause\FromClause;
use Wolfgang\SQL\Clause\SelectClause;
use Wolfgang\SQL\Clause\WhereClause;
use Wolfgang\SQL\Clause\LimitClause;
use Wolfgang\SQL\Clause\OrderByClause;
use Wolfgang\SQL\Clause\GroupByClause;
use Wolfgang\SQL\Clause\HavingClause;
use Wolfgang\Interfaces\SQL\Clause\ISelectClause;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\SQL\Expression\ConditionalExpressionGroup;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Util\Strings;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Exceptions\UnsupportedOperationException;
use Wolfgang\Application\Application;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class SelectStatement extends Statement implements ISelectStatement {

	/**
	 *
	 * @var ISelectClause
	 */
	protected $select_clause;

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
	 * @var IHavingClause
	 */
	protected $having_clause;

	/**
	 *
	 * @var IGroupByClause
	 */
	protected $group_by_clause;

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
	 * @var ISelectStatement
	 */
	protected $parent;

	/**
	 *
	 * @param ITable $table
	 */
	public function __construct ( ITable $table ) {
		$this->from_clause = new FromClause( $this, $table );

		parent::__construct( $table );
	}

	protected function init ( ) {
		parent::init();

		$this->select_clause = new SelectClause( $this );
		$this->where_clause = new WhereClause( $this );
		$this->limit_clause = new LimitClause( $this );
		$this->limit_clause->setLimit( 10 );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::addSelectColumn()
	 */
	public function addSelectColumn ( $column, $alias = null) {
		$this->getSelectClause()->addSelectColumn( $column, $alias );
	}

	/**
	 *
	 * @return array
	 */
	public function getSelectColumns ( ): array {
		return $this->getSelectClause()->getSelectColumns();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getSelectClause()
	 */
	public function getSelectClause ( ): ISelectClause {
		return $this->select_clause;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getFromClause()
	 */
	public function getFromClause ( ): IFromClause {
		return $this->from_clause;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getWhereClause()
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

			if ( ! $expression ) {
				throw new \TypeError( "Return value from where closure should be either an instance of IConditionalExpression or an instance of IConditionalExpressionGroup" );
			}

			$this->getWhereClause()->addExpression( $expression );
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::where()
	 */
	public function where ( $where ): ISelectStatement {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_AND );

		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::andWhere()
	 */
	public function andWhere ( $where ) {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_AND );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::orWhere()
	 */
	public function orWhere ( $where ) {
		$this->_where( $where, IConditionalExpression::LOGICAL_OPERATOR_OR );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getGroupByClause()
	 */
	public function getGroupByClause ( ): IGroupByClause {
		return $this->group_by_clause;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getHavingClause()
	 */
	public function getHavingClause ( ): IHavingClause {
		return $this->having_clause;
	}

	/**
	 *
	 * @param array $group_by
	 */
	private function _groupBy ( array $group_by ) {
		if ( $this->group_by_clause == null ) {
			$this->group_by_clause = new GroupByClause( $this );
		}

		foreach ( $group_by as $field ) {
			$this->group_by_clause->addField( $field );
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::groupBy()
	 */
	public function groupBy ( array $group_by ) {
		$this->group_by_clause = new GroupByClause( $this );

		$this->_groupBy( $group_by );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::addGroupBy()
	 */
	public function addGroupBy ( array $group_by ) {
		$this->_groupBy( $group_by );
	}

	/**
	 *
	 * @param array|callable $having
	 * @throws IllegalArgumentException
	 * @throws InvalidArgumentException
	 */
	private function _having ( $having, $operator = IConditionalExpression::LOGICAL_OPERATOR_AND) {
		if ( empty( $having ) ) {
			throw new IllegalArgumentException( "Having condition not provided" );
		} else if ( ! is_array( $having ) && ! is_callable( $having ) ) {
			throw new InvalidArgumentException( "Invalid argument provided for having condition" );
		}

		$expression = null;

		if ( is_array( $having ) ) {
			foreach ( $having as $subject => $condition ) {
				$expression = new ConditionalExpression( $this->getHavingClause(), $operator );

				if ( is_array( $condition ) ) {
					$expression = $expression->in( $subject, $condition );
				} else {
					$expression = $expression->eq( $subject, $condition );
				}
			}
		} else {
			$expression = new ConditionalExpression( $this->getHavingClause(), $operator );
			$expression = $having( $expression );
		}

		$this->getHavingClause()->addExpression( $expression );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::having()
	 */
	public function having ( $having ) {
		$this->having_clause = new HavingClause( $this );

		$this->_having( $having, IConditionalExpression::LOGICAL_OPERATOR_AND );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::andHaving()
	 */
	public function andHaving ( $having ) {
		$this->_having( $having, IConditionalExpression::LOGICAL_OPERATOR_OR );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::orHaving()
	 */
	public function orHaving ( $having ) {
		$this->_having( $having, IConditionalExpression::LOGICAL_OPERATOR_OR );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::orderBy()
	 */
	public function orderBy ( $expression, $order = IOrderByClause::ORDER_ASC) {
		$this->order_by_clause = new OrderByClause( $this );

		$this->order_by_clause->orderBy( $expression, $order );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::addOrderBy()
	 */
	public function addOrderBy ( $expression, $order = IOrderByClause::ORDER_ASC) {
		$this->order_by_clause->addOrderBy( $expression, $order );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getOrderByClause()
	 */
	public function getOrderByClause ( ): IOrderByClause {
		return $this->order_by_clause;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::limit()
	 */
	public function limit ( $limit ) {
		$this->limit_clause = new LimitClause( $this );

		$this->limit_clause->setLimit( $limit );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\ISelectStatement::getLimitClause()
	 */
	public function getLimitClause ( ): ILimitClause {
		return $this->limit_clause;
	}

	/**
	 *
	 * @param string $statement
	 * @param array $schemas The schemas the statement is to be created for and to/will be executed
	 *        on
	 * @throws InvalidArgumentException
	 * @return IStatement|NULL
	 */
	public static function fromString ( string $statement, array $schemas ): ?IStatement {
		if ( empty( $statement ) ) {
			throw new InvalidArgumentException( "Statement not provided" );
		} else if ( empty( $schemas ) ) {
			throw new InvalidArgumentException( "Schemas not provided" );
		} else if ( ! preg_match( self::$dml_statement_syntax_pregs[ 'select' ], $statement ) ) {
			throw new InvalidArgumentException( "Statement is not a select statement" );
		}

		$statement = Strings::remove( [ 
				"\n",
				"\r",
				"\t"
		], $statement );

		preg_match( "/^(SELECT (.*?))(?:[\s]+)FROM ([`]?[\w]+[`]?(?:[\.]))?([`]?[\w]{1,}[`]?)(?:[\s]+)?(WHERE (.*?))?(?:[\s]+)?(GROUP BY (.*?))?(?:[\s]+)?(?:[\s]+)?(HAVING (.*?))?(?:[\s]+)?(ORDER BY (.*?))?(?:[\s]+)?(LIMIT ([\d,\s]+))?\;/i", $statement, $matches );

		if ( empty( $matches ) ) {
			throw new InvalidStateException( "Unable to parse select statement '{$statement}'" );
		}

		throw new UnsupportedOperationException( "public static method 'fromString' of class 'Wolfgang\SQL\Statement\DML\SelectStatement' has not completely been implemented" );

		return $statement;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		try {

			$statement = $this->select_clause;

			$statement .= ' ' . $this->from_clause;

			if ( $this->where_clause && $this->where_clause->getExpressions()->count() ) {
				$statement .= ' ' . $this->where_clause;
			}

			if ( $this->group_by_clause ) {
				$statement .= ' ' . $this->group_by_clause;
			}

			if ( $this->having_clause ) {
				$statement .= ' ' . $this->having_clause;
			}

			if ( $this->order_by_clause ) {
				$statement .= ' ' . $this->order_by_clause;
			}

			if ( $this->limit_clause && $this->limit_clause->getLimit() ) {
				$statement .= ' ' . $this->limit_clause;
			}

			$statement .= ";";

			return $statement;
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}

		return '';
	}
}
