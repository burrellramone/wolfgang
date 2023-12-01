<?php

namespace Wolfgang\ORM\MySQLi;

use Wolfgang\Interfaces\ORM\IMySQLiColumn;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\ORM\Column as ORMColumn;
use Wolfgang\Exceptions\MethodNotImplementedException;

/**
 *
 * @package Wolfgang\ORM\MySQLi
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class Column extends ORMColumn implements IMySQLiColumn {
	
	/**
	 *
	 * @var int
	 */
	protected $flags;
	
	/**
	 *
	 * @var int
	 */
	protected $character_max_limit;
	
	/**
	 *
	 * @param ITable $table
	 * @param array $field_definition
	 */
	public function __construct ( ITable $table, array $field_definition ) {
		parent::__construct( $table, $field_definition );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\ORM\Column::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isNullable()
	 */
	public function isNullable ( ): bool {
		return ! ($this->flags & MYSQLI_NOT_NULL_FLAG);
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isIntegerType()
	 */
	public function isIntegerType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_TINY,
				MYSQLI_TYPE_SHORT,
				MYSQLI_TYPE_LONG,
				MYSQLI_TYPE_INT24,
				MYSQLI_TYPE_LONGLONG
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isFloatType()
	 */
	public function isFloatType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_FLOAT,
				MYSQLI_TYPE_DOUBLE,
				MYSQLI_TYPE_DECIMAL,
				MYSQLI_TYPE_NEWDECIMAL
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isCharType()
	 */
	public function isCharType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_VAR_STRING,
				MYSQLI_TYPE_STRING,
				MYSQLI_TYPE_CHAR
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isGeometryType()
	 */
	public function isGeometryType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_GEOMETRY
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isBitType()
	 */
	public function isBitType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_BIT
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isBlobType()
	 */
	public function isBlobType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_TINY_BLOB,
				MYSQLI_TYPE_MEDIUM_BLOB,
				MYSQLI_TYPE_LONG_BLOB,
				MYSQLI_TYPE_BLOB
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isTextType()
	 */
	public function isTextType ( ): bool {
		throw new MethodNotImplementedException( "" );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isDateTimeType()
	 */
	public function isDateTimeType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_DATETIME,
				MYSQLI_TYPE_TIMESTAMP
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isDateType()
	 */
	public function isDateType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_DATE,
				MYSQLI_TYPE_NEWDATE
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isTimeType()
	 */
	public function isTimeType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_TIME
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isYearType()
	 */
	public function isYearType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_YEAR
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isEnumType()
	 */
	public function isEnumType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_ENUM
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isSetType()
	 */
	public function isSetType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_SET
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isIntervalType()
	 */
	public function isIntervalType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_INTERVAL
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isJsonType()
	 */
	public function isJsonType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_JSON
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isNullType()
	 */
	public function isNullType ( ): bool {
		if ( in_array( $this->getType(), [ 
				MYSQLI_TYPE_NULL
		] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isEncrypted()
	 */
	public function isEncrypted ( ): bool {
		return ($this->getFlags() & MYSQLI_BINARY_FLAG) && ! $this->isBitType();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IMySQLiColumn::getFlags()
	 */
	public function getFlags ( ): int {
		return $this->flags;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\IColumn::getCharacterMaxLimit()
	 */
	public function getCharacterMaxLimit ( ): ?int {
		return $this->character_max_limit;
	}
}
