<?php

namespace Wolfgang\Traits\Model;

use Wolfgang\Interfaces\ORM\IColumn;

/**
 *
 * @package Component
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
trait TEncrypted {
	
	/**
	 *
	 * @see \Wolfgang\Interfaces\Model\IEncrypted::isEncryptedColumn()
	 * @param IColumn $column
	 * @return bool
	 */
	public function isEncryptedColumn ( IColumn $column ): bool {
		return in_array( $column->getName(), $this->getEncryptedColumns() );
	}
}
