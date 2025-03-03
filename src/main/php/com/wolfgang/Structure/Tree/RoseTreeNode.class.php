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
	 * @param string|int|float $name
	 * @param ITreeNode|null $parent
	 */
    public function __construct ( string|int|float $name, ITreeNode|null $parent = null ) {
		parent::__construct( $name, $parent );
	}
}