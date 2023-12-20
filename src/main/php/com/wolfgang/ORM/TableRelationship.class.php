<?php

namespace Wolfgang\ORM;

use Wolfgang\Interfaces\ORM\ITableRelationship;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Model\Manager as ModelManager;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class TableRelationship extends Component implements ITableRelationship {
	/**
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 *
	 * @var string
	 */
	protected $table_schema_name;
	
	/**
	 *
	 * @var string
	 */
	protected $table_name;
	
	/**
	 *
	 * @var string
	 */
	protected $column_name;
	
	/**
	 *
	 * @var string
	 */
	protected $referenced_table_schema_name;
	
	/**
	 *
	 * @var string
	 */
	protected $referenced_table_name;
	
	/**
	 *
	 * @var string
	 */
	protected $referenced_column_name;
	
	/**
	 *
	 * @var string
	 */
	protected $delete_rule;
	
	/**
	 *
	 * @var string
	 */
	protected $update_rule;
	
	/**
	 *
	 * @var array
	 */
	private $relationship;
	
	/**
	 *
	 * @param array $relationship
	 */
	public function __construct ( array $relationship ) {
		$this->setRelationship( $relationship );
		
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->setName( $this->relationship[ 'constraint_name' ] );
		
		$this->setTableSchemaName( $this->relationship[ 'table_schema_name' ] );
		$this->setTableName( $this->relationship[ 'table_name' ] );
		$this->setColumnName( $this->relationship[ 'column_name' ] );
		
		$this->setReferencedTableSchemaName( $this->relationship[ 'referenced_table_schema_name' ] );
		$this->setReferencedTableName( $this->relationship[ 'referenced_table_name' ] );
		$this->setReferencedColumnName( $this->relationship[ 'referenced_column_name' ] );
		
		if ($this->relationship['update_rule']) {
			$this->setUpdateRule( $this->relationship[ 'update_rule' ] );
		}

		if ($this->relationship['delete_rule']) {
			$this->setDeleteRule( $this->relationship[ 'delete_rule' ] );
		}

		unset( $this->relationship );
	}
	
	/**
	 *
	 * @param string $name
	 */
	private function setName ( string $name ) {
		$this->name = $name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITableRelationship::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}
	
	/**
	 *
	 * @param string $table_schema_name
	 */
	private function setTableSchemaName ( string $table_schema_name ) {
		$this->table_schema_name = $table_schema_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getTableSchemaName ( ): string {
		return $this->table_schema_name;
	}
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getTableSchema ( ): IDatabaseSchema {
		return SchemaManager::getInstance()->get( $this->getTableSchemaName() );
	}
	
	/**
	 *
	 * @access private
	 * @param string $table_name
	 */
	private function setTableName ( $table_name ) {
		$this->table_name = $table_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getTableName ( ): string {
		return $this->table_name;
	}
	
	/**
	 *
	 * @return ITable
	 */
	public function getTable ( ): ITable {
		return $this->getTableSchema()->getTable( $this->getTableName() );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\ITableRelationship::getTableModel()
	 * @see IModel
	 * @return IModel
	 */
	public function getTableModel ( ): IModel {
		$model = ModelManager::getInstance()->create( $this->getTableName() );
		
		return $model;
	}
	
	/**
	 *
	 * @param string $column_name
	 */
	private function setColumnName ( $column_name ) {
		$this->column_name = $column_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getColumnName ( ): string {
		return $this->column_name;
	}
	
	/**
	 *
	 * @param string $referenced_table_schema_name
	 */
	private function setReferencedTableSchemaName ( string $referenced_table_schema_name ) {
		$this->referenced_table_schema_name = $referenced_table_schema_name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITableRelationship::getReferencedTableSchemaName()
	 */
	public function getReferencedTableSchemaName ( ): string {
		return $this->referenced_table_schema_name;
	}
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getReferencedTableSchema ( ): IDatabaseSchema {
		return SchemaManager::getInstance()->get( $this->getReferencedTableSchemaName() );
	}
	
	/**
	 *
	 * @param string $referenced_table_name
	 */
	private function setReferencedTableName ( $referenced_table_name ) {
		$this->referenced_table_name = $referenced_table_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getReferencedTableName ( ): string {
		return $this->referenced_table_name;
	}
	
	/**
	 * Gets an instance of the referenced table in this table relationship
	 *
	 * @return ITable
	 */
	public function getReferencedTable ( ): ITable {
		return $this->getReferencedTableSchema()->getTable( $this->getReferencedTableName() );
	}
	
	/**
	 * Gets an instance of the model the referenced table represents
	 *
	 * {@inheritdoc}
	 *
	 * @see ITableRelationship::getReferencedTableModel()
	 * @see IModel
	 * @return IModel
	 */
	public function getReferencedTableModel ( ): IModel {
		$model = ModelManager::getInstance()->create( $this->getReferencedTableName() );
		
		return $model;
	}
	
	/**
	 *
	 * @param string $referenced_column_name
	 */
	private function setReferencedColumnName ( $referenced_column_name ) {
		$this->referenced_column_name = $referenced_column_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getReferencedColumnName ( ): string {
		return $this->referenced_column_name;
	}
	
	/**
	 *
	 * @access private
	 * @param array $relationship
	 */
	private function setRelationship ( array $relationship ) {
		$this->relationship = $relationship;
	}
	
	/**
	 *
	 * @param string $update_rule
	 */
	private function setUpdateRule ( string $update_rule ) {
		$this->update_rule = $update_rule;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getUpdateRule ( ): string {
		return $this->update_rule;
	}
	
	/**
	 *
	 * @param string $delete_rule
	 */
	private function setDeleteRule ( string $delete_rule ) {
		$this->delete_rule = $delete_rule;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getDeleteRule ( ): string {
		return $this->delete_rule;
	}
}
