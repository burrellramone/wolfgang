<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Interfaces\SQL\Expression\IExpression;
use Wolfgang\SQL\Component as SQLComponent;
use Wolfgang\Interfaces\SQL\Clause\IClause;
use Wolfgang\Date\DateTime;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement;
use Wolfgang\Exceptions\ORM\TableNotExistException;
use Wolfgang\Interfaces\SQL\Clause\IDeleteClause;
use Wolfgang\Interfaces\SQL\Clause\IFromClause;
use Wolfgang\Interfaces\SQL\Clause\ISelectClause;
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\StringObject;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Expression extends SQLComponent implements IExpression {
	
	/**
	 *
	 * @var IClause
	 */
	protected $clause;
	
	/**
	 *
	 * @var string|int|array|double|DateTime
	 */
	protected $expression;
	
	/**
	 *
	 * @var IExpression
	 */
	protected $parent;
	
	/**
	 *
	 * @param IClause $clause
	 * @param string|int|array|double|DateTime $expression
	 */
	protected function __construct ( IClause $clause, $expression = null ) {
		$this->setClause( $clause );
		
		if ( $expression !== null ) {
			$this->setExpression( $expression );
		}
		
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @param \Wolfgang\Interfaces\SQL\Clause\IClause $clause
	 */
	private function setClause ( IClause $clause ) {
		$this->clause = $clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Expression\IExpression::getClause()
	 */
	public function getClause ( ): IClause {
		return $this->clause;
	}
	
	/**
	 *
	 * @access protected
	 * @param string|int|array|double|DateTime $expression
	 * @throws IllegalArgumentException
	 */
	protected function setExpression ( $expression ) {
		if ( $expression === null ) {
			throw new IllegalArgumentException( 'Expression must be provided' );
		}
		
		$this->expression = $expression;
	}
	
	/**
	 *
	 * @param string|int|array|double|DateTime $expression
	 * @return IExpression
	 */
	public static function create ( IClause $clause, $expression ): IExpression {
		if ( is_int( $expression ) || is_double( $expression ) || is_float( $expression ) ) {
			return new NumericExpression( $clause, $expression );
		} else if ( ($expression instanceof DateTime) || ($expression === "CURRENT_TIMESTAMP") ) {
			return new DateTimeExpression( $clause, $expression );
		} else if ( is_object( $expression ) || is_array( $expression ) ) {
			return new StringExpression( $clause, $expression );
		} else if ( is_bool( $expression ) ) {
			return new BooleanExpression( $clause, $expression );
		} else if ( $expression === null ) {
			return new BooleanExpression( $clause, $expression );
		} else if ( is_string( $expression ) || ($expression instanceof StringObject)) {
			if ( empty( $expression ) ) {
				return new CharacterExpression( $clause, $expression );
			} else if ( preg_match( "/^[\s]+$/", $expression ) ) { //Expression is a string of one or more space characters
				if ( strlen( $expression ) == 1 ) {
					return new CharacterExpression( $clause, $expression );
				} else {
					return new StringExpression( $clause, $expression );
				}
			} else if ( preg_match( "/^([\w]+)([\s]*)?\((.*)\)([\s]+)?$/i", $expression ) ) {
				return new FunctionExpression( $clause, $expression );
			} else if ( !($expression instanceof StringObject) && preg_match( "/^([a-z_]+\.[a-z_]+)$/", $expression, $matches ) ) { //We're seeing if the expression is one of a qualified column (looking for '.' in the expression)
				$column_parts = explode( '.', $matches[ 0 ] );
				
				if ( ($clause instanceof IDeleteClause) ) {
					$schema = $clause->getStatement()->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema();
				} else if ( ($clause instanceof IFromClause) ) {
					$schema = $clause->getTableReferences()->offsetGet( 0 )->getSchema();
				} else if ( ($clause instanceof IInsertClause) ) {
					$schema = $clause->getTableReference()->getSchema();
				} else if ( ($clause instanceof ISelectClause) ) {
					throw new ComponentException( "Unable to retrieve schema from select clause" );
				} else if ( ($clause instanceof IUpdateClause) ) {
					$schema = $clause->getTableReference()->getSchema();
				} else if ( ($clause instanceof IWhereClause) ) {
					$statement = $clause->getStatement();
					
					if ( ($statement instanceof ISelectStatement) ) {
						$schema = $statement->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema();
					} else if ( ($statement instanceof IUpdateStatement) ) {
						$schema = $statement->getUpdateClause()->getTableReference()->getSchema();
					} else {
						throw new ComponentException( "Unable to retrieve schema using statement" );
					}
				} else {
					throw new ComponentException( "Unable to retrieve schema" );
				}
				
				try {
					if ( empty( $column_parts[ 0 ] ) ) {
						throw new InvalidStateException( "Column part at index 0 is empty" );
					}
					
					$table = $schema->getTable( $column_parts[ 0 ] );
					
					if ( ! ($table->isColumn( $column_parts[ 1 ] )) ) {
						return new StringExpression( $clause, $expression );
					}
				} catch ( TableNotExistException $e ) {
					return new StringExpression( $clause, $expression );
				}
				
				return new ColumnExpression( $clause, $expression );
			}
			
			if ( ($clause instanceof IWhereClause) ) {
				$statement = $clause->getStatement();
				
				if ( ($statement instanceof ISelectStatement) ) {
					$table_reference = $statement->getFromClause()->getTableReferences()->offsetGet( 0 );
				} else if ( ($statement instanceof IUpdateStatement) ) {
					$table_reference = $statement->getUpdateClause()->getTableReference();
				} else if ( ($statement instanceof IDeleteStatement) ) {
					$table_reference = $statement->getFromClause()->getTableReferences()->offsetGet( 0 );
				} else {
					throw new InvalidStateException( "Could not find reference by statement" );
				}
				
				if ( ! $table_reference->isColumn( $expression ) ) {
					if ( strlen( $expression ) == 1 ) {
						return new CharacterExpression( $clause, $expression );
					} else {
						return new StringExpression( $clause, $expression );
					}
				}
			} else if ( ($clause instanceof IInsertClause) || ($clause instanceof IUpdateClause) ) {
				if ( ! $clause->getTableReference()->isColumn( $expression ) ) {
					if ( strlen( $expression ) == 0 || strlen( $expression ) == 1 ) {
						return new CharacterExpression( $clause, $expression );
					} else {
						return new StringExpression( $clause, $expression );
					}
				}
			} // else {
			  // This is a name of a field on the first joined table from the from clause of the
			  // statement. The problme is eventhough the expression matches the name of a column
			  // it may not be a column expression. How do we know this is a field expression and
			  // not simple a literal. ex. first_name = 'first_name'. We need some way to
			  // determine that the second 'first_name' is not a column expression eventhough
			  // there is a column on the table called first_name. Maybe pass in an expression as
			  // literal (new class/object) from the method that calls this one?
			return new ColumnExpression( $clause, $expression );
			// }
		}
	}
}
