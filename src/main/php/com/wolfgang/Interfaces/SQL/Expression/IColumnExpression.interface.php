<?php

namespace Wolfgang\Interfaces\SQL\Expression;

use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\ORM\IColumn;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IColumnExpression extends IExpression {
	
	/**
	 *
	 * @return string
	 */
	public function getTableName ( ): string;
	
	/**
	 *
	 * @return ITable
	 */
	public function getTable ( ): ITable;
	
	/**
	 *
	 * @return string
	 */
	public function getColumnName ( ): string;
	
	/**
	 *
	 * @return IColumn
	 */
	public function getColumn ( ): IColumn;
}