<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Clause\IGroupByClause;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class GroupByClause extends Clause implements IGroupByClause {

	protected $columns = [ ];

	/**
	 *
	 * @param string $column
	 */
	public function addField ( $column ) {
		if ( ! $column ) {
			throw new IllegalArgumentException( "Field not provided" );
		} else if ( ! is_string( $column ) ) {
			throw new InvalidArgumentException( "Invalid argument {$column} provided for column" );
		}

		$this->columns[] = $column;
	}

	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		if ( ! ($statement instanceof ISelectStatement) ) {
			throw new InvalidArgumentException( "Statement not an instance of Wolfgang\Interfaces\SQL\Statement\ISelectStatement" );
		}

		parent::__construct( $statement );
	}

	public function __toString ( ) {
		$clause = "\nGROUP BY ";

		$clause .= implode( ' , ', $this->columns );

		return $clause;
	}
}
