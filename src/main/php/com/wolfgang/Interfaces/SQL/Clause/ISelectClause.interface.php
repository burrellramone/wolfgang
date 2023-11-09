<?php

namespace Wolfgang\Interfaces\SQL\Clause;

/**
 *
 * @package Wolfgang\Interfaces\SQL\Clause
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface ISelectClause extends IClause {
	
	/**
	 *
	 * @param string $column
	 * @param string|null $alias
	 */
	public function addSelectColumn ( $column, $alias = null );
	
	/**
	 *
	 * @return array
	 */
	public function getSelectColumns ( ): array;
}