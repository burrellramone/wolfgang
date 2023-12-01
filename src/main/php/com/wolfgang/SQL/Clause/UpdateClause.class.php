<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;

/**
 *
 * @package Wolfgang\SQL\Clause
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class UpdateClause extends Clause implements IUpdateClause {
	
	/**
	 *
	 * @var ITable
	 */
	protected $table_reference;
	
	/**
	 *
	 * @param IStatement $statement
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IStatement $statement, ITable $table ) {
		if ( ! ($statement instanceof IUpdateStatement) ) {
			throw new InvalidArgumentException( "Statement is not an instance of Wolfgang\Interfaces\SQL\Statement\IUpdateStatement" );
		}
		parent::__construct( $statement );
		
		$this->setTableReference( $table );
	}
	
	/**
	 *
	 * @param ITable $table
	 */
	private function setTableReference ( ITable $table ) {
		$this->table_reference = $table;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\IUpdateClause::getTableReference()
	 */
	public function getTableReference ( ): ITable {
		return $this->table_reference;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		return "UPDATE " . $this->getTableReference()->getName();
	}
}
