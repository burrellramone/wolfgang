<?php

namespace Wolfgang\SQL\Expression;

use Stringable;

//Wolfgang
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Util\LatLng;
use Wolfgang\Application\Application;
use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;
use Wolfgang\Interfaces\SQL\Clause\ISelectClause;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class StringExpression extends CharacterExpression {

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Expression\CharacterExpression::__toString()
	 */
	public function __toString ( ) {
		$expression = null;

		try {
			$connection = null;
			$clause = $this->getClause();

			if ( ($clause instanceof IDeleteClause) ) {
				$connection = $clause->getStatement()->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema()->getConnection();
			} else if ( ($clause instanceof IFromClause) ) {
				$connection = $clause->getTableReferences()->offsetGet( 0 )->getSchema()->getConnection();
			} else if ( ($clause instanceof IInsertClause) ) {
				$connection = $clause->getTableReference()->getSchema()->getConnection();
			} else if ( ($connection instanceof ISelectClause) ) {
				throw new ComponentException( "Unable to retrieve connection from select clause" );
			} else if ( ($clause instanceof IUpdateClause) ) {
				$connection = $clause->getTableReference()->getSchema()->getConnection();
			} else if ( ($clause instanceof IWhereClause) ) {
				$statement = $clause->getStatement();

				if ( ($statement instanceof ISelectStatement) || ($statement instanceof IDeleteStatement) ) {
					$connection = $statement->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema()->getConnection();
				} else if ( ($statement instanceof IUpdateStatement) ) {
					$connection = $statement->getUpdateClause()->getTableReference()->getSchema()->getConnection();
				} else {
					throw new ComponentException( "Unable to retrieve connection using statement" );
				}
			} else {
				throw new ComponentException( "Unable to retrieve connection" );
			}

			if ( is_string( $this->expression ) ) {
				$expression = $this->expression;
				$expression = "\"" . $connection->escape( $expression ) . "\"";
			} else if ( is_array( $this->expression ) ) {
				if ( empty( $this->expression ) ) {
					$expression = '(\'\')';
				} else {
					if(isset( $this->expression[0] )){
						if ( is_int( $this->expression[ 0 ] ) || is_double( $this->expression[ 0 ] ) || is_float( $this->expression[ 0 ] ) ) {
							$expression = "(" . implode( ',', $this->expression ) . ")";
						} else if ( is_string( $this->expression[ 0 ] ) ) {
							$expression = "('" . implode( "','", $this->expression ) . "')";
						} else if ( is_object( $this->expression[ 0 ] ) && ( $this->expression[ 0 ] instanceof Stringable) ) {
							$a = array();
							foreach($this->expression as $v) {
								$a[] = (string)$v;
							}

							$expression = "('" . serialize( $a ) . "')";
						} else {
							throw new \Exception( "Condition not implemented" );
						}
					} else { //Associative array
						$expression = "('" . serialize( $this->expression ) . "')";
					}
					
				}
			} else if ( is_object( $this->expression ) ) {
				if ( ($this->expression instanceof LatLng) ) {
					$expression = "ST_GeomFromText('POINT({$this->expression->getLat()} {$this->expression->getLng()})')";
				} else {
					$expression = "'" . ( string ) $this->expression . "'";
				}
			} else {
				throw new IllegalStateException( "Expression is not a string, object nor an array" );
			}
		} catch ( \Exception $e ) {
			Application::getInstance()->respond( $e );
		}

		return $expression;
	}
}