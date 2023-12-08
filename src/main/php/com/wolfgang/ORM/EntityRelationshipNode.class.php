<?php

namespace Wolfgang\ORM;

use Wolfgang\Structure\Graph\GraphNode;
use Wolfgang\Interfaces\ORM\ISchema as IORMSChema;
use Wolfgang\Interfaces\ISchema;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class EntityRelationshipNode extends GraphNode {
	
	/**
	 *
	 * @param ISchema $schema
	 * @param string $name
	 */
	public function __construct ( ISchema $schema, string $name ) {
		if ( ! ($schema instanceof IORMSChema) ) {
			throw new InvalidArgumentException( "Schema must be an instance of 'Wolfgang\Interfaces\ORM\ISchema'" );
		}
		
		parent::__construct( $schema, $name );
	}
}