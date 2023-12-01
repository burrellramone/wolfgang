<?php

namespace Wolfgang\Interfaces\ORM;

use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IQueryBuilder {

	/**
	 *
	 * @param array|callable $where
	 */
	public function where ( $where ): IQueryBuilder;

	/**
	 *
	 * @param array|callable $where
	 */
	public function andWhere ( $where ): IQueryBuilder;

	/**
	 *
	 * @param array|callable $where
	 */
	public function orWhere ( $where ): IQueryBuilder;

	/**
	 *
	 * @param array $group_by
	 */
	public function groupBy ( array $group_by ): IQueryBuilder;

	/**
	 *
	 * @param array $group_by
	 */
	public function addGroupBy ( array $group_by ): IQueryBuilder;

	/**
	 *
	 * @param array|callable $having
	 */
	public function having ( $having ): IQueryBuilder;

	/**
	 *
	 * @param array|callable $having
	 */
	public function andHaving ( $having ): IQueryBuilder;

	/**
	 *
	 * @param array|callable $having
	 */
	public function orHaving ( $having ): IQueryBuilder;

	/**
	 *
	 * @param string $expression
	 * @param int $order
	 */
	public function orderBy ( $expression, $order = IOrderByClause::ORDER_ASC );

	/**
	 *
	 * @param string $expression
	 * @param int $order
	 */
	public function addOrderBy ( $expression, $order = IOrderByClause::ORDER_ASC );

	/**
	 *
	 * @param int|array $limit
	 */
	public function limit ( $limit );
}
