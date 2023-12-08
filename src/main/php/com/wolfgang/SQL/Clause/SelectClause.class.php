<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\SQL\Clause\ISelectClause;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class SelectClause extends Clause implements ISelectClause {
	
	/**
	 *
	 * @var array
	 */
	protected $columns = [ ];
	
	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		if ( ! ($statement instanceof ISelectStatement) ) {
			throw new InvalidArgumentException( "Statement is not an instance of Wolfgang\Interfaces\SQL\Statement\ISelectStatement" );
		}
		
		parent::__construct( $statement );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Clause\ISelectClause::addSelectColumn()
	 */
	public function addSelectColumn ( $column, $alias = null) {
		$this->columns[ $column ] = $alias;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Clause\ISelectClause::getSelectColumns()
	 */
	public function getSelectColumns ( ): array {
		return $this->columns;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		$clause = "SELECT SQL_CALC_FOUND_ROWS ";
		$columns = [ ];
		
		foreach ( $this->columns as $column => $alias ) {
			if ( $alias ) {
				$columns[] = "{$column} as {$alias}";
			} else {
				$columns[] = " {$column} ";
			}
		}
		
		$clause .= implode( ',', $columns );
		
		return $clause;
	}
}
