<?php

namespace Wolfgang\Interfaces\SQL\Clause;

interface ILimitClause extends IClause {
	/**
	 *
	 * @var integer
	 */
	const LIMIT_NONE = 10000000000000000;
	
	/**
	 *
	 * @param int|array $limit
	 */
	public function setLimit ( $limit );
	
	/**
	 *
	 * @return int|array
	 */
	public function getLimit ( );
}