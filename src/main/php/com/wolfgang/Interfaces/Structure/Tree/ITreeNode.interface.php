<?php

namespace Wolfgang\Interfaces\Structure\Tree;

use Wolfgang\Interfaces\Structure\INode;

interface ITreeNode extends INode {
	
	/**
	 *
	 * @return bool
	 */
	public function isRoot ( ): bool;
	
	/**
	 *
	 * @return ITreeNode
	 */
	public function getParent ( );
	
	/**
	 *
	 * @return \ArrayObject
	 */
	public function getSiblings ( ): \ArrayObject;
	
	/**
	 *
	 * @return \ArrayObject
	 */
	public function getChildren ( ): \ArrayObject;
	
	/**
	 *
	 * @param ITreeNode $node
	 */
	public function addChild ( ITreeNode $node );
	
	/**
	 *
	 * @param int $depth
	 */
	public function setDepth ( $depth );
	
	/**
	 *
	 * @return int
	 */
	public function getDepth ( ): int;
}
