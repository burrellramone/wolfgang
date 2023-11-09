<?php

namespace Wolfgang\Interfaces\Structure\Tree;

interface ITree {
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;
	
	/**
	 *
	 * @return ITreeNode
	 */
	public function getRoot ( ): ITreeNode;
	
	/**
	 *
	 * @param ITree $tree
	 * @param ITreeNode $node
	 * @param callable $callable
	 */
	public function traverse ( ITree $tree, ITreeNode $node, $callable );
	
	/**
	 *
	 * @return bool
	 */
	public function isTraversing ( ): bool;
	
	/**
	 *
	 * @return null
	 */
	public function stopTraverse ( );
}
