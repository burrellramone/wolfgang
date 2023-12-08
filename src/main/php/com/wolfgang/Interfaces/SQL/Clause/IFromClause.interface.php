<?php

namespace Wolfgang\Interfaces\SQL\Clause;

use Wolfgang\Interfaces\ORM\ITable;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IFromClause extends IClause {

	const JOIN_TYPE_LEFT_OUTER = 'LEFT OUTER JOIN';
	const JOIN_TYPE_RIGHT_OUTER = 'RIGHT OUTER JOIN';
	const JOIN_TYPE_INNER = 'INNER JOIN';

	/**
	 *
	 * @param ITable $table
	 * @param string $join_type
	 * @param string $as
	 */
	public function joinTable ( ITable $table, $join_type = IFromClause::JOIN_TYPE_LEFT_OUTER, $as = null );

	/**
	 *
	 * @return \ArrayObject
	 */
	public function getTableReferences ( ): \ArrayObject;
}