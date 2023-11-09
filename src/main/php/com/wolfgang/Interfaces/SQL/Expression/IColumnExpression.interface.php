<?php

namespace Wolfgang\Interfaces\SQL\Expression;

use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\ORM\IColumn;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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