<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITreeNode;
use Wolfgang\Structure\Node;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\ISchema;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class TreeNode extends Node implements ITreeNode {
	
	/**
	 *
	 * @var ITreeNode
	 */
	protected $parent;
	
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $siblings;
	
	/**
	 *
	 * @var \ArrayObject
	 */
	protected $children;
	
	/**
	 *
	 * @var int
	 */
	protected $depth = 1;
	
	/**
	 *
	 * @param ISchema $schema
	 * @param string $name
	 * @param ?ITreeNode $parent
	 */
	public function __construct (ISchema $schema, string $name, ?ITreeNode $parent = null) {
		parent::__construct( $schema, $name );
		
		$this->siblings = new \ArrayObject();
		$this->children = new \ArrayObject();
		$this->parent = $parent;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::isRoot()
	 */
	public function isRoot ( ): bool {
		return ! $this->getParent();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::getParent()
	 */
	public function getParent ( ) {
		return $this->parent;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::getSiblings()
	 */
	public function getSiblings ( ): \ArrayObject {
		return $this->siblings;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::getChildren()
	 */
	public function getChildren ( ): \ArrayObject {
		return $this->children;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::addChild()
	 */
	public function addChild ( ITreeNode $node ) {
		$node->setDepth( $this->getDepth() + 1 );
		
		$this->children->append( $node );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::setDepth()
	 */
	public function setDepth ( $depth ) {
		if ( ! is_int( $depth ) ) {
			throw new IllegalArgumentException( "Depth must be an integer" );
		} else if ( ! $depth ) {
			throw new InvalidArgumentException( "Depth must be 1 or greater" );
		} else if ( ($depth > 1) && ! ($this->getParent()) ) {
			throw new InvalidArgumentException( "Depth cannot be greather than one (1) is there is no parent for the node. Please with the parent of the node." );
		}
		
		$this->depth = $depth;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITreeNode::getDepth()
	 */
	public function getDepth ( ): int {
		return $this->depth;
	}
}