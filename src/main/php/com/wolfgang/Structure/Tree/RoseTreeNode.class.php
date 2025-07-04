<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITreeNode;
use Wolfgang\Interfaces\ISchema;

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
	 * @param ?ITreeNode $parent
	 */
    public function __construct (ISchema $schema, string|int|float $name, ?ITreeNode $parent = null ) {
		parent::__construct($schema, $name, $parent );
	}
}