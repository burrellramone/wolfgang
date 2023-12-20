<?php

namespace Wolfgang\Interfaces\SQL\Statement\DML;

use Wolfgang\Date\DateTime;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IInsertStatement extends IStatement {
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_LOW_PRIORITY = 1;
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_HIGH_PRIORITY = 2;
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_DELAYED = 4;
	
	/**
	 *
	 * @var integer
	 */
	const MODIFIER_IGNORE = 8;
	
	/**
	 *
	 * @param string $column
	 * @param int|string|float|DateTime $value
	 * @param $encrypt bool
	 */
	public function bind ( $column, $value, $encrypt = false );
	
	/**
	 *
	 * @return void
	 */
	public function lowPiority ( );
	
	/**
	 *
	 * @return void
	 */
	public function delayed ( );
	
	/**
	 *
	 * @return void
	 */
	public function highPriority ( );
	
	/**
	 *
	 * @return void
	 */
	public function ignore ( );
	
	/**
	 *
	 * @param string $partition
	 * @return void
	 */
	public function partition ( string $partition );
	
	/**
	 *
	 * @return IInsertClause
	 */
	public function getInsertClause ( ): IInsertClause;
	
	/**
	 *
	 * @param array $columns
	 */
	public function onDuplicateKeyUpdate ( array $columns );
}
