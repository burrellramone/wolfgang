<?php

namespace Wolfgang\Interfaces\ORM;

use Wolfgang\Interfaces\ISchema as IComponentSchema;
use Wolfgang\Interfaces\Database\IConnection as IDatabaseConnection;
use Wolfgang\Interfaces\Network\IDsn;
use Wolfgang\Exceptions\ORM\TableNotExistException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
interface ISchema extends IComponentSchema {
	
	/**
	 *
	 * @exception TableNotExistException
	 * @param string $table_name
	 * @return ITable
	 */
	public function getTable ( string $table_name );
	
	/**
	 *
	 * @return \ArrayObject
	 */
	public function getTables ( ): \ArrayObject;
	
	/**
	 * Determines whether or not a table by a particular name exists within this schema.
	 *
	 * @param string $table_name The name of the table to check if exists
	 * @return bool True if the table exists within this schema, false otherwise
	 */
	public function tableExists ( string $table_name ): bool;
	
	/**
	 *
	 * @return IDatabaseConnection
	 */
	public function getConnection ( ): IDatabaseConnection;
	
	/**
	 *
	 * @return IDsn
	 */
	public function getDsn ( ): IDsn;
	
	/**
	 *
	 * @return string
	 */
	public function getDatabase ( ): string;
	
	/**
	 *
	 * @return ISchema
	 */
	public function getInformationSchema ( ): ISchema;
}
