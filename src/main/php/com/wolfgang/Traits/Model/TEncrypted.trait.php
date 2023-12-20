<?php

namespace Wolfgang\Traits\Model;

use Wolfgang\Interfaces\ORM\IColumn;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
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
