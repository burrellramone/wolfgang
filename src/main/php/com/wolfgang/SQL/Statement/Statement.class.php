<?php

namespace Wolfgang\SQL\Statement;

use Wolfgang\SQL\Component as SQLComponent;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\SQL\Statement\Utility\Statement as UtilityStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\SQL\Statement\DML\Statement as DMLStatement;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Statement extends SQLComponent implements IStatement {
	
	/**
	 *
	 * @var array
	 */
	protected $schemas = [ ];
	
	/**
	 *
	 * @var int
	 */
	protected $modifiers;
	
	/**
	 *
	 * @var array
	 */
	protected static $statement_syntax_pregs = [ 
			"ddl" => "",
			"dml" => "/^(CALL|DELETE|DO|HANDLE|IMPORT TABLE|INSERT|LOAD DATA INFILE|LOAD XML|REPLACE|SELECT|WITH)/i",
			"tcl" => "",
			"utility" => "/^(DESCRIBE|DESC|EXPLAIN|HELP|USE)/i"
	];
	
	/**
	 *
	 * @param array $schema
	 */
	protected function setSchemas ( array $schemas ) {
		// $this->schemas = $schemas;
	}
	
	/**
	 *
	 * @param string $statement
	 * @param array $schemas The schemas the statement is to be created for and to/will be executed
	 *        on
	 * @throws InvalidArgumentException
	 * @return IStatement|NULL
	 */
	public static function fromString ( string $statement, array $schemas ): ?IStatement {
		if ( empty( $schemas ) ) {
			throw new InvalidArgumentException( "Schemas not provided" );
		}
		
		if ( preg_match( self::$statement_syntax_pregs[ 'utility' ], $statement ) ) {
			return UtilityStatement::fromString( $statement, $schemas );
		} else if ( preg_match( self::$statement_syntax_pregs[ 'dml' ], $statement ) ) {
			return DMLStatement::fromString( $statement, $schemas );
		} else {
			throw new InvalidArgumentException( "Could not identify SQL statement by syntax" );
		}
	}
}