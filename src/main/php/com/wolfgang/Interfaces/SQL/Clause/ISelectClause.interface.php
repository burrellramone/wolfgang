<?php

namespace Wolfgang\Interfaces\SQL\Clause;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
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