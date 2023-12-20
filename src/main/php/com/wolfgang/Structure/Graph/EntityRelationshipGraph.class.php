<?php

namespace Wolfgang\Structure\Graph;

use Wolfgang\Interfaces\Structure\Graph\IEntityRelationshipGraph;
use Wolfgang\Interfaces\ISchema;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class EntityRelationshipGraph extends Graph implements IEntityRelationshipGraph {
	
	/**
	 *
	 * @var ISchema
	 */
	protected $schema;
	
	/**
	 *
	 * @param ISchema $schema
	 */
	public function __construct ( ISchema $schema ) {
		$this->setSchema( $schema );
		
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @param ISchema $schema
	 */
	private function setSchema ( ISchema $schema ) {
		$this->schema = $schema;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Structure\Graph\IEntityRelationshipGraph::getSchema()
	 */
	public function getSchema ( ): ISchema {
		return $this->schema;
	}
}
