<?php

namespace Wolfgang\Interfaces\SQL\Statement\DML;

use Wolfgang\Date\DateTime;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\ILimitClause;

/**
 *
 * @package Wolfgang\Wolfgang\Interfaces\SQL\DML
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IUpdateStatement extends IStatement {
	
	/**
	 *
	 * @return IUpdateClause
	 */
	public function getUpdateClause ( ): IUpdateClause;
	
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
	 * @return IWhereClause
	 */
	public function getWhereClause ( ): IWhereClause;
	
	/**
	 *
	 * @param string $column
	 * @param int|string|float|DateTime $value
	 * @param $encrypt bool
	 */
	public function bind ( $column, $value, $encrypt = false );
	
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