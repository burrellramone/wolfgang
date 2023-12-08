<?php
namespace Wolfgang\Interfaces\Structure\Graph;

use Wolfgang\Interfaces\ISchema;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
 interface IEntityRelationshipGraph {
	 
 	/**
 	 * 
* @return ISchema
 	 */
	 public function getSchema () : ISchema;
	 
 }