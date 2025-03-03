<?php

namespace Wolfgang\SQL\Statement\Utility;

use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Proxy\ORM\Table as TableProxy;
use Wolfgang\Exceptions\UnsupportedOperationException;
use Wolfgang\Exceptions\ORM\TableNotExistException;
use Wolfgang\ORM\SchemaManager;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Explain extends Statement {
	
	/**
	 *
	 * @var ITable
	 */
	private $table;
	
	
	/**
	 * 
	 * @param array $schemas
	 * @param ITable|null $table
	 */
	public function __construct ( array $schemas, ITable|null $table = null ) {
		if ( $table ) {
			$this->setTable( $table );
		}
		
		parent::__construct( $schemas );
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
	 * @return ITable
	 */
	public function getTable ( ): ITable {
		return $this->table;
	}
	
	/**
	 *
	 * @param string $statement
	 * @param array $schemas The schemas the statement is to be created for and to/will be executed
	 *        on
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 * @throws UnsupportedOperationException
	 * @return IStatement|NULL
	 */
	public static function fromString ( string $statement, array $schemas ): ?IStatement {
		if ( empty( $statement ) ) {
			throw new InvalidArgumentException( "Statement not provided" );
		} else if ( empty( $schemas ) ) {
			throw new InvalidArgumentException( "Schemas not provided" );
		} else if ( ! preg_match( self::$utility_statement_syntax_pregs[ 'explain' ], $statement ) ) {
			throw new InvalidArgumentException( "Statement is not an explain statement" );
		}
		
		// Describing/Explaining table
		if ( preg_match( "/^(EXPLAIN|DESC|DESCRIBE) ([`]?([\w]+)[`]?[.])?[`]?([\w]+)[`]?/i", $statement, $matches ) ) {
			$schema_name = $matches[ 3 ];
			$table_name = $matches[ 4 ];
			$table = null;
			
			foreach ( SchemaManager::getInstance()->getSchemas() as $schema ) {
				if ( $schema->tableExists( $table_name ) ) {
					$table = new TableProxy( $schema, $table_name );
					break;
				}
			}
			
			if ( ! $table ) {
				throw new TableNotExistException( "Could not find table '{$table_name}' in any provided schemas" );
			}
			
			$statement = new Explain( $schemas, $table );
		} else {
			throw new UnsupportedOperationException( "Converting non-explain-table statements have not been implemented" );
		}
		
		return $statement;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\SQL\Statement\Utility\Statement::__toString()
	 */
	public function __toString ( ) {
		if ( $this->getTable() ) {
			$statement = "DESCRIBE `{$this->getTable()->getSchema()->getName()}`.`{$this->getTable()->getName()}`";
		} else {
			throw new UnsupportedOperationException( "Not implemented" );
		}
		
		return $statement;
	}
}
