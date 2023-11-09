<?php
namespace Wolfgang\Interfaces\Structure\Graph;

use Wolfgang\Interfaces\ISchema;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
 interface IEntityRelationshipGraph {
	 
 	/**
 	 * 
* @return ISchema
 	 */
	 public function getSchema () : ISchema;
	 
 }