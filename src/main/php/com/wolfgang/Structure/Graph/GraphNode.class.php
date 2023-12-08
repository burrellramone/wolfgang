<?php

namespace Wolfgang\Structure\Graph;

use Wolfgang\Interfaces\Structure\Graph\IGraphNode;
use Wolfgang\Structure\Node;
use Wolfgang\Interfaces\Structure\Graph\IEntityRelationship;
use Wolfgang\Interfaces\ISchema;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
class GraphNode extends Node implements IGraphNode {
	
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $relationships;
	
	/**
	 *
	 * @param ISchema $schema
	 * @param string $name
	 */
	public function __construct ( ISchema $schema, string $name ) {
		parent::__construct( $schema, $name );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->relationships = new \ArrayObject();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Graph\IGraphNode::addRelationship()
	 */
	public function addRelationship ( IEntityRelationship $relationship ) {
		foreach ( $this->relationships as $r ) {
			if ( $r->e2->getId() == $relationship->e2->getId() ) {
				return;
			}
		}
		
		$this->relationships->append( $relationship );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Graph\IGraphNode::getRelationships()
	 */
	public function getRelationships ( ): \ArrayObject {
		return $this->relationships;
	}
}
