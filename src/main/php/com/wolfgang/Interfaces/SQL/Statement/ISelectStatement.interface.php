<?php

namespace Wolfgang\Interfaces\SQL\Statement;

use Wolfgang\Interfaces\SQL\Expression\IConditionalExpression;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\IHavingClause;
use Wolfgang\Interfaces\SQL\Clause\IGroupByClause;
use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;
use Wolfgang\Interfaces\SQL\Clause\ILimitClause;
use Wolfgang\Interfaces\SQL\Clause\ISelectClause;

/**
 *
 * @package Wolfgang\Interfaces\SQL\Statement
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface ISelectStatement extends IStatement {
	const MODIFIER_ALL = 1;
	const MODIFIER_DISTINCT = 2;
	const MODIFIER_DISTINCT_ROW = 2;
	const MODIFIER_HIGH_PRIORITY = 4;
	const MODIFIER_STRAIGHT_JOIN = 8;
	const MODIFIER_SQL_SMALL_RESULT = 16;
	const MODIFIER_SQL_BIG_RESULT = 32;
	const MODIFIER_SQL_BUFFER_RESULT = 64;
	const MODIFIER_SQL_CALC_FOUND_ROWS = 128;

	/**
	 *
	 * @param string $column
	 * @param string $alias
	 */
	public function addSelectColumn ( $column, $alias = null);

	/**
	 *
	 * @return ISelectClause
	 */
	public function getSelectClause ( ): ISelectClause;

	/**
	 *
	 * @return IFromClause
	 */
	public function getFromClause ( ): IFromClause;

	/**
	 *
	 * @return IWhereClause
	 */
	public function getWhereClause ( ): IWhereClause;

	/**
	 *
	 * @param array|callable $where
	 */
	public function where ( $where ): ISelectStatement;

	/**
	 *
	 * @param array|callable $where
	 */
	public function andWhere ( $where );

	/**
	 *
	 * @param array|callable $where
	 */
	public function orWhere ( $where );

	/**
	 *
	 * @param array|IConditionalExpression $having
	 */
	public function having ( $having );

	/**
	 *
	 * @param array|IConditionalExpression $having
	 */
	public function andHaving ( $having );

	/**
	 *
	 * @param array|IConditionalExpression $having
	 */
	public function orHaving ( $having );

	/**
	 *
	 * @return IHavingClause
	 */
	public function getHavingClause ( ): IHavingClause;

	/**
	 *
	 * @param array $group_by
	 */
	public function groupBy ( array $group_by );

	/**
	 *
	 * @param array $group_by
	 */
	public function addGroupBy ( array $group_by );

	/**
	 *
	 * @return IGroupByClause
	 */
	public function getGroupByClause ( ): IGroupByClause;

	/**
	 *
	 * @param string $field
	 * @param int $order
	 */
	public function orderBy ( $field, $order = IOrderByClause::ORDER_ASC);

	/**
	 *
	 * @param string $field
	 * @param int $order
	 */
	public function addOrderBy ( $field, $order = IOrderByClause::ORDER_ASC);

	/**
	 *
	 * @return IOrderByClause
	 */
	public function getOrderByClause ( ): IOrderByClause;

	/**
	 *
	 * @param int|array $limit
	 */
	public function limit ( $limit );

	/**
	 *
	 * @return ILimitClause
	 */
	public function getLimitClause ( ): ILimitClause;
}
