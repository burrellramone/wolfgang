<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITree;
use Wolfgang\Interfaces\Structure\Tree\ITreeNode;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Structure\Tree
 * @since Version 1.0.0
 */
abstract class Tree extends Component implements ITree {
	/**
	 *
	 * @var ITreeNode
	 */
	protected $root;
	
	/**
	 *
	 * @var bool
	 */
	protected $is_traversing = false;
	
	/**
	 *
	 * @param ITreeNode $root
	 */
	public function __construct ( ITreeNode $root ) {
		parent::__construct();
		
		$this->root = $root;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITree::getName()
	 */
	public function getName ( ): string {
		if ( $this->root ) {
			return $this->getRoot()->getName();
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITree::getRoot()
	 */
	public function getRoot ( ): ITreeNode {
		return $this->root;
	}
	
	/**
	 *
	 * @return bool
	 */
	public function isTraversing ( ): bool {
		return $this->is_traversing;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITree::traverse()
	 */
	public function traverse ( ITree $tree, ITreeNode $node, $callable ) {
		if ( ! $this->is_traversing ) {
			$this->is_traversing = true;
		}
		
		$callable( $tree, $node );
		
		foreach ( $node->getChildren() as $child ) {
			if ( ! $this->isTraversing() ) {
				return;
			}
			
			$this->traverse( $tree, $child, $callable );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\Tree\ITree::stopTraverse()
	 */
	public function stopTraverse ( ) {
		$this->is_traversing = false;
	}
	
	/**
	 *
	 * @param string|int|float|double $node_name
	 * @return \Wolfgang\Interfaces\Structure\Tree\ITreeNode
	 */
	public function find ( $node_name ) {
		return $this->findRecursively( $node_name, $this->getRoot() );
	}
	
	/**
	 *
	 * @param string|int|float|double $node_name
	 * @param ITreeNode $node
	 * @return \Wolfgang\Interfaces\Structure\Tree\ITreeNode
	 */
	private function findRecursively ( string $node_name, ITreeNode $node ):?ITreeNode {
		if ( $node->getName() == $node_name ) {
			$n = &$node;
			return $n;
		}
		
		foreach ( $node->getChildren() as $child ) {
			return $this->findRecursively( $node_name, $child );
		}

		return null;
	}
}