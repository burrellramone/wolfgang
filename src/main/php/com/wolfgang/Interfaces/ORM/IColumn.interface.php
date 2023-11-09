<?php

namespace Wolfgang\Interfaces\ORM;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IColumn {
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;
	
	/**
	 * Gets the fully qualified name for this column
	 *
	 * @return string
	 */
	public function getQualifiedName ( ): string;
	
	/**
	 *
	 * @return ITable
	 */
	public function getTable ( ): ITable;
	
	/**
	 *
	 * @return mixed
	 */
	public function getDefaultValue ( );
	
	/**
	 * Gets the maximum number of characters that are allowed for this column.
	 *
	 * @see \Wolfgang\Interfaces\ORM\IColumn::isCharType()
	 * @return int|NULL The maximum number of characters that are allowed for this column if the
	 *         column is a character type column, null otherwise
	 */
	public function getCharacterMaxLimit ( ): ?int;
	
	/**
	 *
	 * @return bool
	 */
	public function isNullable ( ): bool;
	
	/**
	 *
	 * @return int
	 */
	public function getType ( ): int;
	
	/**
	 *
	 * @return bool
	 */
	public function isIntegerType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isFloatType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isCharType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isGeometryType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isBitType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isDateType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isTimeType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isYearType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isEnumType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isSetType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isIntervalType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isJsonType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isBlobType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isTextType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isDateTimeType ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isNullType ( ): bool;
	
	/**
	 * Determines whether or not this column hold encrypted data.
	 *
	 * @return bool True if the column holds encrypted data, false otherwise
	 */
	public function isEncrypted ( ): bool;
}
