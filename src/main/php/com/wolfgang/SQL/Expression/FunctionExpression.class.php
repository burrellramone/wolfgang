<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Exceptions\SQL\ColumnNotExistException;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Application\Application;

/**
 *
 * @package Wolfgang\SQL\Expression
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class FunctionExpression extends Expression {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Expression\Expression::init()
	 */
	protected function init ( ) {
		parent::init();
		
		if ( preg_match( "/^[\w]+\((?:|.*,)([^'][\w]+\.[\w]+[^'])(?:|,.*)?\)$/", $this->expression, $matches ) ) {
			$first_joined_table = $this->getClause()->getStatement()->getFromClause()->getTableReferences()->offsetGet( 0 );
			
			array_shift( $matches );
			
			foreach ( $matches as $match ) {
				$column_parts = explode( '.', $match );
				
				if ( $column_parts[ 0 ] != $first_joined_table->getName() ) {
					
					$column_table = null;
					$statement = $this->getClause()->getStatement();
					
					if ( ($statement instanceof ISelectStatement) ) {
						$column_table = $statement->getFromClause()->getTableReferences()->offsetGet( 0 )->getSchema();
					} else {
						throw new \Exception( "Could not identify statement type" );
					}
					
					if ( ! $column_table || ! $column_table->isColumn( $column_parts[ 1 ] ) ) {
						throw new ColumnNotExistException( "The column '{$column_parts[1]}' does not belong to the table '{$column_parts[0]}'. Qualify the column with its respective table name." );
					}
					
					$this->getClause()->getStatement()->getFromClause()->joinTable( $column_table );
				}
			}
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		try {
			return $this->expression;
		} catch ( \Exception $e ) {
			Application::getInstanceance()->respond( $e );
		}
	}
}