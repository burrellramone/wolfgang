<?php

namespace Wolfgang\SQL\Expression;

use Wolfgang\Interfaces\SQL\Clause\IClause;
use Wolfgang\Date\DateTime;
use Wolfgang\Exceptions\SQL\ColumnNotExistException;
use Wolfgang\Interfaces\SQL\Clause\IWhereClause;
use Wolfgang\Interfaces\SQL\Clause\IUpdateClause;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;
use Wolfgang\Interfaces\SQL\Expression\IColumnExpression;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\ORM\IColumn;
use Wolfgang\Interfaces\SQL\Statement\ISelectStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IUpdateStatement;
use Wolfgang\Interfaces\SQL\Statement\DML\IDeleteStatement;
use Wolfgang\Interfaces\Model\IEncrypted;
use Wolfgang\Application\Application;
use Wolfgang\ORM\SchemaManager;
use Wolfgang\Exceptions\ORM\TableNotExistException;
use Wolfgang\Exceptions\InvalidStateException;

/**
 *
 * @package Wolfgang\SQL\Expression
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class ColumnExpression extends Expression implements IColumnExpression {
	
	/**
	 *
	 * @var ITable
	 */
	protected $table;
	
	/**
	 *
	 * @var string
	 */
	protected $table_name;
	
	/**
	 *
	 * @var IColumn
	 */
	protected $column;
	
	/**
	 *
	 * @var string
	 */
	protected $column_name;
	
	/**
	 *
	 * @param IClause $clause
	 * @param string|array|int|DateTime $expression
	 */
	public function __construct ( IClause $clause, $expression ) {
		parent::__construct( $clause, $expression );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Expression\Expression::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$clause_table = null;
		$clause = $this->getClause();
		
		if ( ($clause instanceof IInsertClause) || ($clause instanceof IUpdateClause) ) {
			$clause_table = $clause->getTableReference();
		} else if ( ($clause instanceof IWhereClause) ) {
			$statement = $clause->getStatement();
			
			if ( ($statement instanceof ISelectStatement) ) {
				$clause_table = $clause->getStatement()->getFromClause()->getTableReferences()->offsetGet( 0 );
			} else if ( ($statement instanceof IInsertStatement) ) {
				$clause_table = $statement->getInsertClause()->getTableReference();
			} else if ( ($statement instanceof IUpdateStatement) ) {
				$clause_table = $statement->getUpdateClause()->getTableReference();
			} else if ( ($statement instanceof IDeleteStatement) ) {
				$clause_table = $statement->getFromClause()->getTableReferences()->offsetGet( 0 );
			} else {
				throw new InvalidStateException( "Could not find clause table by statement" );
			}
		}
		
		$matches = [ ];
		
		preg_match( "/^(([a-z_]+\.)?[a-z_]+)$/", $this->expression, $matches );
		
		$column_parts = explode( '.', $matches[ 0 ] );
		
		if ( count( $column_parts ) == 2 ) {
			
			$this->setTableName( $column_parts[ 0 ] );
			$column_expression_table = $this->getTable();
			
			if ( $clause_table->getName() != $column_expression_table->getName() ) {
				if ( ! $column_expression_table->isColumn( $column_parts[ 1 ] ) ) {
					throw new ColumnNotExistException( "The column '{$column_parts[1]}' does not belong to the table '{$column_expression_table->getName()}'. Qualify the column with its respective table name." );
				}
				
				if ( ($clause instanceof IWhereClause) ) {
					$clause->getStatement()->getFromClause()->joinTable( $column_expression_table );
				}
			}
		} else {
			if ( ! $clause_table->isColumn( $column_parts[ 0 ] ) ) {
				throw new ColumnNotExistException( "The column '{$column_parts[0]}' does not belong to the table '{$clause_table->getName()}'. Qualify the column with its respective table name." );
			}
			
			$this->setTableName( $clause_table->getName() );
			$this->setExpression( $clause_table->getName() . '.' . $column_parts[ 0 ] );
		}
	}
	
	/**
	 *
	 * @param ITable $table
	 */
	private function setTable ( ITable $table ) {
		$this->table = $table;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Expression\IColumnExpression::getTable()
	 */
	public function getTable ( ): ITable {
		if ( ! $this->table ) {
			foreach ( SchemaManager::getInstance()->getSchemas() as $schema ) {
				try {
					$this->table = $schema->getTable( $this->getTableName() );
					if ( $this->table ) {
						break;
					}
				} catch ( TableNotExistException $e ) {
				}
			}
		}
		
		return $this->table;
	}
	
	/**
	 *
	 * @param string $table_name
	 */
	private function setTableName ( string $table_name ) {
		$this->table_name = $table_name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Expression\IColumnExpression::getTableName()
	 */
	public function getTableName ( ): string {
		return $this->table_name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Expression\IColumnExpression::getColumnName()
	 */
	public function getColumnName ( ): string {
		return $this->getColumn()->getName();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Expression\IColumnExpression::getColumn()
	 */
	public function getColumn ( ): IColumn {
		if ( ! $this->column ) {
			$column_name = explode( '.', $this->expression );
			
			if ( count( $column_name ) == 2 ) {
				$column_name = $column_name[ 1 ];
			} else {
				$column_name = $column_name[ 0 ];
			}
			
			$column_name = $this->column = $this->getTable()->getColumn( $column_name );
		}
		return $this->column;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		try {
			
			$table = $this->getColumn()->getTable();
			$model = $table->getModel();
			$encryption_key = $table->getSchema()->getDsn()->getEncryptionKey();
			
			if ( ($model instanceof IEncrypted) && $model->isEncryptedColumn( $this->getColumn() ) ) {
				return "AES_DECRYPT({$this->expression}, '{$encryption_key}')";
			}
		} catch ( \Exception $e ) {
			die( $e->getMessage() );
			Application::getInstance()->respond( $e );
		}
		
		return $this->expression;
	}
}
