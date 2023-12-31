<?php

namespace Wolfgang\Interfaces\Structure\Graph;

use Wolfgang\Interfaces\Structure\INode;
use Wolfgang\Interfaces\IEntity;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IGraphNode extends INode , IEntity {
	
	/**
	 *
	 * @param IEntityRelationship $relationship
	 */
	public function addRelationship ( IEntityRelationship $relationship );
	
	/**
	 *
	 * @return \ArrayObject
	 */
	public function getRelationships ( ): \ArrayObject;
}
