<?php

namespace Wolfgang\Interfaces\ORM;

use Wolfgang\Interfaces\ISchema;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IRelationalSchema extends ISchema {
	/**
	 *
	 * @param string $table_name
	 * @return ITable
	 */
	public function getTable ( $table_name );
}