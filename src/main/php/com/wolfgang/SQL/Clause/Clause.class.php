<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\SQL\Component as SQLComponent;
use Wolfgang\Interfaces\SQL\Clause\IClause;
use Wolfgang\Interfaces\SQL\Statement\IStatement;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Clause extends SQLComponent implements IClause {
	
	/**
	 *
	 * @var IStatement
	 */
	protected $statement;
	
	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		parent::__construct();
		
		$this->setStatement( $statement );
	}
	
	/**
	 *
	 * @param IStatement $statement
	 */
	private function setStatement ( IStatement $statement ) {
		$this->statement = $statement;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\IClause::getStatement()
	 */
	public function getStatement ( ): IStatement {
		return $this->statement;
	}
}
