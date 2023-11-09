<?php

namespace Wolfgang\SQL\Statement\DML;

use Wolfgang\Interfaces\SQL\Statement\DML\IDMLStatement;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\IBitManipulator;
use Wolfgang\SQL\Statement\Statement as ComponentSQLStatement;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\UnsupportedOperationException;

/**
 *
 * @package Wolfgang\SQL\Statement\DML
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
abstract class Statement extends ComponentSQLStatement implements IDMLStatement , IBitManipulator {
	/**
	 *
	 * @var array
	 */
	protected static $dml_statement_syntax_pregs = [ 
			"select" => "/^(SELECT )/i" 
	];
	
	/**
	 *
	 * @var int
	 */
	protected $modifiers = 0;
	
	/**
	 *
	 * @param ITable $table
	 */
	public function __construct ( ITable $table ) {
		parent::__construct();
		
		$this->setSchemas( [ 
				$table->getSchema() 
		] );
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
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IBitManipulator::on()
	 */
	public function on ( int $bit ) {
		$this->modifiers = $this->modifiers | $bit;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IBitManipulator::off()
	 */
	public function off ( int $bit ) {
		$this->modifiers = $this->modifiers & $bit;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IBitManipulator::isOn()
	 */
	public function isOn ( int $bit ): bool {
		return ($this->modifiers & $bit) == $bit;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IBitManipulator::isOff()
	 */
	public function isOff ( int $bit ): bool {
		return ! $this->isOn( $bit );
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
		if ( ! preg_match( self::$statement_syntax_pregs[ 'dml' ], $statement ) ) {
			throw new InvalidArgumentException( "Statement is not a data manipulation statement" );
		}
		
		if ( preg_match( self::$dml_statement_syntax_pregs[ 'select' ], $statement ) ) {
			return SelectStatement::fromString( $statement, $schemas );
		} else {
			throw new UnsupportedOperationException( "Unhandled DML statement" );
		}
	}
}
