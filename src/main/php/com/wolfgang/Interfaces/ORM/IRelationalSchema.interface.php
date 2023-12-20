<?php

namespace Wolfgang\Interfaces\ORM;

use Wolfgang\Interfaces\ISchema;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IRelationalSchema extends ISchema {
	/**
	 *
	 * @param string $table_name
	 * @return ITable
	 */
	public function getTable ( $table_name );
}