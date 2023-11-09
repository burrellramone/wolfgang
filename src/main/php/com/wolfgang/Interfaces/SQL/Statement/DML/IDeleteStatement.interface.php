<?php

namespace Wolfgang\Interfaces\SQL\Statement\DML;

use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\ILimitClause;
use Wolfgang\Interfaces\SQL\Clause\IOrderByClause;
use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;

/**
 *
 * @package Wolfgang\Interfaces\SQL\DML\Statement
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IDeleteStatement extends IStatement {
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_LOW_PRIORITY = 1;
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_QUICK = 2;
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_IGNORE = 4;
	
	public function lowPriority ( );
	
	public function quick ( );
	
	public function ignore ( );
	
	/**
	 *
	 * @return IDeleteClause
	 */
	public function getDeleteClause ( ): IDeleteClause;
	
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
	public function where ( $where );
	
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
	 * @param string $field
	 * @param int $order
	 */
	public function orderBy ( $field, $order = IOrderByClause::ORDER_ASC );
	
	/**
	 *
	 * @param string $field
	 * @param int $order
	 */
	public function addOrderBy ( $field, $order = IOrderByClause::ORDER_ASC );
	
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