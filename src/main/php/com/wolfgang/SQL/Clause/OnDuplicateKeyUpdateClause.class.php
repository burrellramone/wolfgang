<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class OnDuplicateKeyUpdateClause extends Clause {
	/**
	 *
	 * @var array
	 */
	protected $columns;
	
	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement, array $columns ) {
		if ( ! ($statement instanceof IInsertStatement) ) {
			throw new InvalidArgumentException( "Statement not an instance of Wolfgang\Interfaces\SQL\Statement\IInsertStatement" );
		}
		
		parent::__construct( $statement );
		
		$this->setColumns( $columns );
	}
	
	/**
	 *
	 * @param array $columns
	 * @throws InvalidArgumentException
	 */
	private function setColumns ( array $columns ) {
		if ( empty( $columns ) ) {
			throw new InvalidArgumentException( "Columns to update cannot be empty" );
		}
		
		$this->columns = $columns;
	}
	
	public function __toString ( ) {
		$clause = "ON DUPLICATE KEY UPDATE ";
		$values = [ ];
		
		foreach ( $this->columns as $column ) {
			if ( $column == 'id' ) {
				$values[] = "id = id";
			} else {
				$values[] = "{$column} = VALUES({$column})";
			}
		}
		
		$clause .= " " . implode( ',', $values );
		
		return $clause;
	}
}