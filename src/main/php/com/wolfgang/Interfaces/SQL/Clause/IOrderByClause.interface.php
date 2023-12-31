<?php

namespace Wolfgang\Interfaces\SQL\Clause;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IOrderByClause extends IClause {
	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';
	
	/**
	 *
	 * @param string $field
	 * @param string $order
	 */
	public function orderBy ( $field, $order = IOrderByClause::ORDER_ASC );
	
	/**
	 *
	 * @param string $field
	 * @param string $order
	 */
	public function addOrderBy ( $field, $order = IOrderByClause::ORDER_ASC );
}
