<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITreeNode;

/**
 *
 * 
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
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