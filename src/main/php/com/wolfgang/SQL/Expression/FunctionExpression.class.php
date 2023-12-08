<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Exceptions\SQL\ColumnNotExistException;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Application\Application;
use Wolfgang\ORM\SchemaManager;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
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
				
				//The table referenced in the function is not the one specified in the from clause. eg. FROM <table_name>
				if ( $column_parts[ 0 ] != $first_joined_table->getName() ) {

				    //The table referenced in the function will be in the same schema as the first joined table
					$schema = SchemaManager::getInstance()->getByDatabaseName($first_joined_table->getSchemaDsnName());
					$column_table = $schema->getTable($column_parts[ 0 ]);
					
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
