<?php

namespace Wolfgang\SQL\Clause;

use Wolfgang\Interfaces\SQL\Clause\ILimitClause;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Exceptions\SQL\Clause\Exception as SQLClauseException;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class LimitClause extends Clause implements ILimitClause {

	/**
	 *
	 * @var int|array
	 */
	protected $limit = 1;

	/**
	 *
	 * @param IStatement $statement
	 */
	public function __construct ( IStatement $statement ) {
		parent::__construct( $statement );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\ILimitClause::setLimit()
	 * @throws SQLClauseException
	 * @throws InvalidArgumentException::
	 */
	public function setLimit ( $limit ) {
		if ( is_array( $limit ) ) {
			if ( empty( $limit[ 0 ] ) && $limit[ 0 ] != 0 ) {
				throw new SQLClauseException( "Limit clause offset not provided" );
			} else if ( empty( $limit[ 1 ] ) ) {
				throw new SQLClauseException( "Limit clause row count provided" );
			} else if ( ! is_numeric( $limit[ 0 ] ) || ! is_numeric( $limit[ 1 ] ) ) {
				throw new InvalidArgumentException( "Numeric values expected for limit clause." );
			}
		} else if ( ! is_numeric( $limit ) ) {
			throw new InvalidArgumentException( "Numeric value expected for limit clause." );
		}

		$this->limit = $limit;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Clause\ILimitClause::getLimit()
	 */
	public function getLimit ( ) {
		return $this->limit;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		$limit_clause = "\nLIMIT ";

		if ( is_array( $this->limit ) ) {
			$limit_clause .= "{$this->limit[0]},{$this->limit[1]}";
		} else {
			$limit_clause .= "{$this->limit}";
		}

		return $limit_clause;
	}
}
