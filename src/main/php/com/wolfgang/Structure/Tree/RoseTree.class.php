<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITreeNode;

/**
 *
 * @package Wolfgang\Structure\Tree
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
class RoseTree extends Tree {

	/**
	 *
	 * @param ITreeNode $root
	 */
	public function __construct ( ITreeNode $root ) {
		parent::__construct( $root );
	}
}