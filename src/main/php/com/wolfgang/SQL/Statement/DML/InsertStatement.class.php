<?php

namespace Wolfgang\SQL\Statement\DML;

use Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\SQL\Expression\Expression;
use Wolfgang\Interfaces\SQL\Clause\IInsertClause;
use Wolfgang\SQL\Clause\InsertClause;
use Wolfgang\SQL\Clause\OnDuplicateKeyUpdateClause;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class InsertStatement extends Statement implements IInsertStatement {
	
	/**
	 *
	 * @var InsertClause
	 */
	protected $insert_clause;
	
	/**
	 *
	 * @var OnDuplicateKeyUpdateClause
	 */
	protected $on_duplicate_key_update_clause;
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $bound_columns;
	
	/**
	 *
	 * @var array
	 */
	protected $partitions = [ ];
	
	/**
	 *
	 * @param ITable $table
	 */
	public function __construct ( ITable $table ) {
		$this->insert_clause = new InsertClause( $this, $table );
		
		parent::__construct( $table );
	}
	
	protected function init ( ) {
		parent::init();
		
		$this->bound_columns = new \ArrayObject();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::highPriority()
	 */
	public function highPriority ( ) {
		$this->off( IInsertStatement::MODIFIER_LOW_PRIORITY );
		$this->off( IInsertStatement::MODIFIER_DELAYED );
		$this->on( IInsertStatement::MODIFIER_HIGH_PRIORITY );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::lowPiority()
	 */
	public function lowPiority ( ) {
		$this->off( IInsertStatement::MODIFIER_HIGH_PRIORITY );
		$this->off( IInsertStatement::MODIFIER_DELAYED );
		$this->on( IInsertStatement::MODIFIER_LOW_PRIORITY );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::delayed()
	 */
	public function delayed ( ) {
		$this->off( IInsertStatement::MODIFIER_HIGH_PRIORITY );
		$this->off( IInsertStatement::MODIFIER_LOW_PRIORITY );
		$this->on( IInsertStatement::MODIFIER_DELAYED );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::partition()
	 */
	public function partition ( string $partition ) {
		if ( ! $partition ) {
			throw new IllegalArgumentException( "Partition name must be provided" );
		} else if ( ! is_string( $partition ) ) {
			throw new InvalidArgumentException( "Partition name must be a string" );
		}
		
		$this->partitions[] = $partition;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::ignore()
	 */
	public function ignore ( ) {
		$this->on( IInsertStatement::MODIFIER_IGNORE );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::getInsertClause()
	 */
	public function getInsertClause ( ): IInsertClause {
		return $this->insert_clause;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::onDuplicateKeyUpdate()
	 */
	public function onDuplicateKeyUpdate ( array $columns ) {
		$this->on_duplicate_key_update_clause = new OnDuplicateKeyUpdateClause( $this, $columns );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\SQL\Statement\DML\IInsertStatement::bind()
	 */
	public function bind ( $column, $value, $encrypt = false) {
		if ( ! $column ) {
			throw new IllegalArgumentException( "Column name must be provided" );
		} else if ( ! is_string( $column ) ) {
			throw new InvalidArgumentException( "Column name must be a string" );
		} else if ( ! $this->getInsertClause()->getTableReference()->isColumn( $column ) ) {
			throw new InvalidArgumentException( "Unknown column '{$column}' of table {$this->getInsertClause()->getTableReference()->getName()}" );
		}
		
		$this->bound_columns->append( [ 
				'column' => $column,
				'expression' => Expression::create( $this->insert_clause, $value ),
				'encrypt' => $encrypt
		] );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		$encryption_key = $this->getInsertClause()->getTableReference()->getSchema()->getDsn()->getEncryptionKey();
		
		$statement = ( string ) $this->getInsertClause();
		$statement .= ' (`';
		
		$columns = [ ];
		$values = [ ];
		
		foreach ( $this->bound_columns as $bound_column ) {
			$column = $bound_column[ 'column' ];
			$encrypt = $bound_column[ 'encrypt' ];
			$value = ( string ) $bound_column[ 'expression' ];
			
			$columns[] = $column;
			
			if ( $encrypt ) {
				$values[] = "AES_ENCRYPT( $value, '{$encryption_key}')";
			} else {
				$values[] = $value;
			}
		}
		
		$statement .= implode( "`,`", $columns );
		
		$statement .= '`) VALUES ( ';
		
		$statement .= implode( ",", $values );
		
		$statement .= " )";
		
		if ( $this->on_duplicate_key_update_clause ) {
			$statement .= " " . $this->on_duplicate_key_update_clause;
		}
		
		return $statement;
	}
}
