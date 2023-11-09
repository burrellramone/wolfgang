<?php

namespace Wolfgang\SQL\Statement\Utility;

use Wolfgang\SQL\Statement\Statement as ComponentSQLStatement;
use Wolfgang\Interfaces\SQL\Statement\Utility\IStatement as IUtilityStatement;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\UnsupportedOperationException;

/**
 *
 * @package Wolfgang\SQL\Statement\Utility
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
abstract class Statement extends ComponentSQLStatement implements IUtilityStatement {
	
	/**
	 *
	 * @var array
	 */
	protected static $utility_statement_syntax_pregs = [ 
			"explain" => "/^(EXPLAIN|DESC|DESCRIBE)/i"
	];
	
	/**
	 *
	 * @param array $schemas The schemas the statement is to be created for and to/will be executed
	 *        on
	 */
	public function __construct ( array $schemas ) {
		parent::__construct();
		
		$this->setSchemas( $schemas );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @param string $statement
	 * @param array $schemas The schemas the statement is to be created for and to/will be executed
	 *        on
	 * @throws InvalidArgumentException
	 * @throws UnsupportedOperationException
	 * @return IStatement|NULL
	 */
	public static function fromString ( string $statement, array $schemas ): ?IStatement {
		if ( ! preg_match( self::$statement_syntax_pregs[ 'utility' ], $statement ) ) {
			throw new InvalidArgumentException( "Statement is not a utility statement" );
		}
		
		if ( preg_match( self::$utility_statement_syntax_pregs[ 'explain' ], $statement ) ) {
			return Explain::fromString( $statement, $schemas );
		} else {
			throw new UnsupportedOperationException( "Unhandled utility statement" );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
	}
}