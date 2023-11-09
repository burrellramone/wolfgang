<?php

namespace Wolfgang\ORM;

use Wolfgang\Interfaces\Structure\Tree\IRoseTree;
use Wolfgang\Interfaces\Structure\Tree\ITreeNode;
use Wolfgang\Structure\Tree\RoseTreeNode;
use Wolfgang\Structure\Tree\RoseTree;
use Wolfgang\Exceptions\MethodNotImplementedException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\ORM
 * @since Version 1.0.0
 */
final class ForeignKeyRelationshipTree extends RoseTree implements IRoseTree {
	
	/**
	 *
	 * @param ITreeNode $root
	 */
	public function __construct ( ITreeNode $root ) {
		parent::__construct( $root );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->addSubTree( $this->getRoot() );
	}
	
	/**
	 *
	 * @param ITreeNode $node
	 */
	private function addSubTree ( ITreeNode $node ) {
		throw new MethodNotImplementedException();
		
		// $table = TableManager::getInstance()->get( $node->getName() );
		
		// $foreign_key_fields_relationships = $table->getForeignKeyFieldsRelationships();
		
		// foreach ( $foreign_key_fields_relationships as $foreign_key_field_relationship ) {
		// $child_name = $foreign_key_field_relationship->getReferencedTableName();
		
		// if ( $child_name == $node->getName() ) {
		// continue;
		// }
		
		// $child = new RoseTreeNode( $child_name, $node );
		// $node->addChild( $child );
		// $this->addSubTree( $child );
		// }
	}
}
