<?php

namespace Wolfgang\Interfaces\ORM;

use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;

interface ITableRelationship {
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;
	
	/**
	 *
	 * @return string
	 */
	public function getTableSchemaName ( ): string;
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getTableSchema ( ): IDatabaseSchema;
	
	/**
	 *
	 * @return string
	 */
	public function getTableName ( ): string;
	
	/**
	 * Gets an instance of the model the subject table represents
	 *
	 * @see IModel
	 * @return IModel
	 */
	public function getTableModel ( ): IModel;
	
	/**
	 *
	 * @return string
	 */
	public function getColumnName ( ): string;
	
	/**
	 *
	 * @return string
	 */
	public function getReferencedTableSchemaName ( ): string;
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getReferencedTableSchema ( ): IDatabaseSchema;
	
	/**
	 *
	 * @return string
	 */
	public function getReferencedTableName ( ): string;
	
	/**
	 * Gets an instance of the model the referenced table represents
	 *
	 * @see IModel
	 * @return IModel
	 */
	public function getReferencedTableModel ( ): IModel;
	
	/**
	 *
	 * @return string
	 */
	public function getReferencedColumnName ( ): string;
}
