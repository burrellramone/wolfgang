<?php

namespace Wolfgang\ORM;

use Wolfgang\Structure\Graph\EntityRelationshipGraph as BaseEntityRelationshipGraph;
use Wolfgang\Structure\Graph\EntityRelationship;
use Wolfgang\Exceptions\ORM\Exception as ORMException;
use Wolfgang\Interfaces\ORM\ISchema;

/**
 *
 * @package Wolfgang\ORM
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
class EntityRelationshipGraph extends BaseEntityRelationshipGraph {
	
	/**
	 *
	 * @param ISchema $schema
	 */
	public function __construct ( ISchema $schema ) {
		parent::__construct( $schema );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Structure\Graph\EntityRelationshipGraph::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$orm_schema = SchemaManager::getInstance()->get( 'orm' );
		$subject_schema = $this->getSchema();
		
		$orm_connection = $orm_schema->getConnection();
		$airportruns_connection = $subject_schema->getConnection();
		
		$result = $airportruns_connection->exec( "SHOW TABLES IN {$this->getSchema()->getName()};" );
		
		foreach ( $result as $record ) {
			$table_name = $record[ "Tables_in_{$this->getSchema()->getName()}" ];
			$table = $subject_schema->getTable( $table_name );
			
			if ( ! $table ) {
				throw new ORMException( "Unable to find table with name '{$table_name}' in schema '{$subject_schema->getName()}'" );
			}
			
			$relationships = $table->getForeignKeyFieldsRelationships();
			
			$node = new EntityRelationshipNode( $this->getSchema(), $table_name );
			
			if ( $relationships->count() ) {
				foreach ( $relationships as $relationship ) {
					$node_name = $relationship->getReferencedTableName();
					$referenced_node = &$this->find( $node_name );
					
					if ( $referenced_node == null ) {
						$referenced_node = new EntityRelationshipNode( $this->getSchema(), $node_name );
					}
					
					$node->addRelationship( new EntityRelationship( $node, $referenced_node ) );
				}
			}
			
			$this->add( $node );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Structure\Graph\Graph::save()
	 */
	public function save ( ) {
		$orm_schema = SchemaManager::getInstance()->get( 'orm' );
		$orm_connection = $orm_schema->getConnection();
		
		$orm_connection->exec( "CREATE DATABASE IF NOT EXISTS `orm`;" );
		$orm_connection->exec( "USE `orm`;" );
		
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS `node` (
				id VARCHAR(36) NOT NULL PRIMARY KEY,
				schema_name VARCHAR(64) NOT NULL,
				name VARCHAR(64) NOT NULL,
				create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				CONSTRAINT UNIQUE (schema_name, name)
			);
SQL;
		
		$orm_connection->exec( $sql );
		$orm_connection->exec( "DELETE FROM `node` WHERE schema_name = '{$this->getSchema()->getName()}';" );
		
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS `node_relationship` (
				`node_id` VARCHAR(36) NOT NULL,
				`related_node_id` VARCHAR(36) NOT NULL,
				`create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				UNIQUE KEY (node_id, related_node_id),
				CONSTRAINT FOREIGN KEY (node_id) REFERENCES node(id) ON DELETE CASCADE
			);
SQL;
		
		$orm_connection->exec( $sql );
		
		foreach ( $this->getMap() as $name => $node ) {
			$sql = <<<SQL
			INSERT INTO `node` (id, schema_name, name, create_date) VALUES ('{$node->getId()}', '{$node->getSchema()->getName()}', '{$node->getName()}', CURRENT_TIMESTAMP)
SQL;
			$orm_connection->exec( $sql );
		}
		
		foreach ( $this->getMap() as $node ) {
			$relationships = $node->getRelationships();
			
			if ( ! $relationships->count() ) {
				echo "No relationships found for node {$node->getName()}\n";
			} else {
				echo "{$relationships->count()} relationships found for node {$node->getName()}\n";
			}
			
			foreach ( $relationships as $relationship ) {
				$sql = <<<SQL
					INSERT INTO `node_relationship` (node_id, related_node_id, create_date) VALUES ('{$node->getId()}', '{$relationship->e2->getId()}', CURRENT_TIMESTAMP)
SQL;
				$orm_connection->exec( $sql );
			}
		}
		
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS `revision` (
				revision BIGINT(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
				create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
			);

SQL;
		$orm_connection->exec( $sql );
		
		$orm_connection->exec( "INSERT INTO `revision` (create_date) VALUES (CURRENT_TIMESTAMP);" );
	}
}
