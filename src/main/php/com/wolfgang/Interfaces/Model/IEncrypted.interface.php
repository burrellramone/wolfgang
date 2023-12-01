<?php

namespace Wolfgang\Interfaces\Model;


use Wolfgang\Interfaces\ORM\IColumn;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
interface IEncrypted extends IModel {
	
	/**
	 * 
	 * @param IColumn $column
	 * @return bool
	 */
	public function isEncryptedColumn(IColumn $column) : bool;
	
	/**
	 * Gets an array of column names from the table the model points to that are or are to be
	 * encrypted
	 *
	 * @return array
	 */
	public function getEncryptedColumns ( ): array;
}
