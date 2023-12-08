<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;
use Wolfgang\Interfaces\SQL\Clause\ISelectClause;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Exceptions\SQL\Exception as SQLException;
use Wolfgang\Application\Application;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
class CharacterExpression extends Expression {

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		try {
			$expression = $this->expression;

			$connection = null;
			$clause = $this->getClause();

			if ( ($clause instanceof IDeleteClause) ) {
				$connection = $clause->getStatement()->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema()->getConnection();
			} else if ( ($clause instanceof IFromClause) ) {
				$connection = $clause->getTableReferences()->offsetGet( 0 )->getSchema()->getConnection();
			} else if ( ($clause instanceof IInsertClause) ) {
				$connection = $clause->getTableReference()->getSchema()->getConnection();
			} else if ( ($connection instanceof ISelectClause) ) {
				throw new SQLException( "Unable to retrieve connection from select clause" );
			} else if ( ($clause instanceof IUpdateClause) ) {
				$connection = $clause->getTableReference()->getSchema()->getConnection();
			} else if ( ($clause instanceof IWhereClause) ) {
				$statement = $clause->getStatement();

				if ( ($statement instanceof ISelectStatement) || ($statement instanceof IDeleteStatement) ) {
					$connection = $statement->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema()->getConnection();
				} else if ( ($statement instanceof IUpdateStatement) ) {
					$connection = $statement->getUpdateClause()->getTableReference()->getSchema()->getConnection();
				} else {
					throw new InvalidStateException( "Unable to retrieve connection using statement" );
				}
			}

			if ( ! $connection ) {
				throw new InvalidStateException( "Could not retrive connection" );
			}
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}

		return "'" . $connection->escape( $expression ) . "'";
	}
}
