<?php

namespace Wolfgang\Interfaces\Structure\Graph;

use Wolfgang\Interfaces\Structure\INode;
use Wolfgang\Interfaces\IEntity;

/**
 *
 * @package Wolfgang\Interfaces
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
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
