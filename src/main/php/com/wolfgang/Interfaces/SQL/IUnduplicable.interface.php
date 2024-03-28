<?php

namespace Wolfgang\Interfaces\SQL;

/**
 * Interface representing a model that exhibits an ON DUPLICATE KEY UPDATE
 * behaviour for when a record is inserted
 * 
 * @see  https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IUnduplicable {
	
	/**
	 *
	 * @return array
	 */
	public function getUpdateColumns ( ): array;
}