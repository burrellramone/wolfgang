<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITreeNode;

/**
 *
 * 
 *
 * @package Wolfgang\Structure\Tree
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class RoseTreeNode extends TreeNode {
	
	/**
	 *
	 * @param string|int|float|double $name
	 * @param ITreeNode $parent
	 */
	public function __construct ( $name, ITreeNode $parent = null ) {
		parent::__construct( $name, $parent );
	}
}