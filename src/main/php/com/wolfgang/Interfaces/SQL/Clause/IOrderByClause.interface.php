<?php

namespace Wolfgang\Interfaces\SQL\Clause;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
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
