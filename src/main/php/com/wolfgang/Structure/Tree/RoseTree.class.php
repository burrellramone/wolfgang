<?php

namespace Wolfgang\Structure\Tree;

use Wolfgang\Interfaces\Structure\Tree\ITreeNode;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
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