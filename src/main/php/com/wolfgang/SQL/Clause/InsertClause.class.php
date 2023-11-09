<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;
use Wolfgang\Interfaces\ORM\ITable;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class InsertClause extends Clause implements IInsertClause {
	
	/**
	 *
	 * @var ITable
	 */
	protected $table_reference;
	
	/**
	 *
	 * @param IStatement $statement
	 * @param ITable $table
	 * @throws InvalidArgumentException
	 */
	public function __construct ( IStatement $statement, ITable $table ) {
		if ( ! ($statement instanceof IInsertStatement) ) {
			throw new InvalidArgumentException( "Statement is not an instance of Wolfgang\Interfaces\SQL\Statement\IInsertStatement" );
		}
		
		$this->table_reference = $table;
		
		parent::__construct( $statement );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\IInsertClause::getTableReference()
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
		return "INSERT INTO `{$this->getTableReference()->getName()}`";
	}
}
