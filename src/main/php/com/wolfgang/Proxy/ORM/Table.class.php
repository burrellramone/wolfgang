<?php

namespace Wolfgang\Proxy\ORM;

use Wolfgang\Interfaces\ORM\ITable;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\ORM\IColumn;
use Wolfgang\Interfaces\ORM\ITableRelationship;
use Wolfgang\Interfaces\ORM\ISchema as IDatabaseSchema;
use Wolfgang\Interfaces\IProxy;
use Wolfgang\Traits\TProxy;
use Wolfgang\ORM\SchemaManager;

/**
 *
 * @package Wolfgang\Proxy\ORM
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
class Table extends Component implements IProxy , ITable {
	use TProxy;
	
	/**
	 * The name of the data source name for the schema this table belongs to. This is used to
	 * retrieved the schema from the manager without storing the schema itself as a member of this
	 * table
	 *
	 * @var string
	 */
	protected $schema_dsn_name;
	
	/**
	 * The name of the proxied table
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * The qualified name for the table
	 *
	 * @var string
	 */
	protected $qualified_name;
	
	/**
	 *
	 * @param string $name
	 */
	public function __construct ( IDatabaseSchema $schema, string $name ) {
		parent::__construct();
		
		$this->setName( $name );
		$this->setQualifiedName( $schema->getDsn()->getDatabase() . "." . $name );
		$this->setSchemaDsnName( $schema->getDsn()->getName() );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @access private
	 */
	protected function load ( ) {
		$this->subject = $this->getSchema()->getTable( $this->getName() );
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
	 * @return string
	 */
	public function getName ( ): string {
		return $this->name;
	}
	
	/**
	 *
	 * @param string $qualified_name
	 */
	private function setQualifiedName ( string $qualified_name ) {
		$this->qualified_name = $qualified_name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getQualifiedName()
	 */
	public function getQualifiedName ( ): string {
		return $this->qualified_name;
	}
	
	/**
	 *
	 * @param string $schema_dsn_name
	 */
	private function setSchemaDsnName ( string $schema_dsn_name ) {
		$this->schema_dsn_name = $schema_dsn_name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSchemaDsnName ( ): string {
		return $this->schema_dsn_name;
	}
	
	/**
	 *
	 * @return IDatabaseSchema
	 */
	public function getSchema ( ): IDatabaseSchema {
		return SchemaManager::getInstance()->get( $this->getSchemaDsnName() );
	}
	
	/**
	 *
	 * @return \ArrayObject
	 */
	public function getColumns ( ): \ArrayObject {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\ITable::getColumn()
	 */
	public function getColumn ( string $column_name ): IColumn {
	}
	
	/**
	 *
	 * @param string $column_name
	 */
	public function isColumn ( string $column_name ): bool {
		return $this->getSubject()->isColumn( $column_name );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getRelationship()
	 */
	public function getRelationship ( ITable $table ): ?ITableRelationship {
		$this->load();
		return $this->getSubject()->getRelationship( $table );
	}
	
	/**
	 *
	 * @param ITable $table
	 * @return \ArrayObject
	 */
	public function getForeignKeyFieldRelationship ( ITable $table ): \ArrayObject {
	}
	
	/**
	 *
	 * @param ITable $table
	 * @return \ArrayObject|null
	 */
	public function getReferencedKeyFieldRelationship ( $table ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getReferencedKeyFieldsRelationships()
	 */
	public function getReferencedKeyFieldsRelationships ( ): \ArrayObject {
		$this->load();
		return $this->getSubject()->getReferencedKeyFieldsRelationships();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getForeignKeyFieldsRelationships()
	 */
	public function getForeignKeyFieldsRelationships ( ): \ArrayObject {
		$this->load();
		$this->getSubject()->getForeignKeyFieldsRelationships();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\ORM\ITable::getModel()
	 */
	public function getModel ( ): IModel {
	}
	
	/**
	 *
	 * @param string $comment
	 */
	private function setComment ( string $comment ) {
		$this->getSubject()->setComment( $comment );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getComment()
	 */
	public function getComment ( ): ?string {
		return $this->getSubject()->getComment();
	}
	
	/**
	 *
	 * @param string $type
	 */
	private function setType ( string $type ) {
		$this->getSubject()->setType();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ORM\ITable::getType()
	 */
	public function getType ( ): string {
		return $this->getSubject()->getType();
	}
}
