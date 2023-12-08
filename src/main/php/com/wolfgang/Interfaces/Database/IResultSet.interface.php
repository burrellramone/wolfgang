<?php

namespace Wolfgang\Interfaces\Database;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface IResultSet extends \Iterator , \Countable {
	
	/**
	 *
	 * @param int $index
	 * @return array
	 */
	public function get ( $index ): array;
	
	/**
	 *
	 * @return array
	 */
	public function fetchAll ( ): array;
	
	/**
	 *
	 * @return array
	 */
	public function getColumns ( ): array;
}
