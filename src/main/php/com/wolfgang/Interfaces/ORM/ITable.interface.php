<?php

namespace Wolfgang\Interfaces\ORM;

use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
interface ITable {

	/**
	 * Gets the name of this table
	 *
	 * @return string
	 */
	public function getName ( ): string;

	/**
	 * Gets the fully qualified name of this table
	 *
	 * @return string
	 */
	public function getQualifiedName ( ): string;

	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getSchema ( ): IDatabaseSchema;

	/**
	 *
	 * @param ITable $table
	 * @return ITableRelationship|NULL
	 */
	public function getRelationship ( ITable $table ): ?ITableRelationship;

	/**
	 *
	 * @return \ArrayObject
	 */
	public function getReferencedKeyFieldsRelationships ( ): \ArrayObject;

	/**
	 *
	 * @return \ArrayObject
	 */
	public function getForeignKeyFieldsRelationships ( ): \ArrayObject;

	/**
	 *
	 * @return \ArrayObject
	 */
	public function getColumns ( ): \ArrayObject;

	/**
	 *
	 * @param string $column_name
	 * @return IColumn
	 */
	public function getColumn ( string $column_name ): ?IColumn;

	/**
	 * Gets an instance of the model this table represents
	 *
	 * @return IModel
	 */
	public function getModel ( ): IModel;

	/**
	 *
	 * @return string|NULL
	 */
	public function getComment ( ): ?string;

	/**
	 *
	 * @return string
	 */
	public function getType ( ): string;
}
