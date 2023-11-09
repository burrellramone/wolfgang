<?php

namespace Wolfgang\Structure;

use Wolfgang\Interfaces\Structure\INode;
use Wolfgang\Structure\Component as StructureComponent;
use Wolfgang\Util\UUID;
use Wolfgang\Interfaces\ISchema;

/**
 *
 * @package Wolfgang\Structure
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
abstract class Node extends StructureComponent implements INode {
	
	/**
	 *
	 * @var string
	 */
	protected $id;
	
	/**
	 *
	 * @var string|int
	 */
	protected $name;
	
	/**
	 *
	 * @var ISchema
	 */
	protected $schema;
	
	/**
	 *
	 * @param ISchema $scheme
	 * @param string|int|double|float $name
	 */
	public function __construct ( ISchema $schema, string $name ) {
		parent::__construct();
		
		$this->setSchema( $schema );
		$this->setName( $name );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();
		$this->id = UUID::id();
	}
	
	/**
	 *
	 * @param string $name
	 */
	private function setName ( $name ) {
		$this->name = $name;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\INode::getName()
	 */
	public function getName ( ) {
		return $this->name;
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
	 * @return ISchema
	 */
	public function getSchema ( ): ISchema {
		return $this->schema;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Structure\INode::getId()
	 */
	public function getId ( ): string {
		return $this->id;
	}
}