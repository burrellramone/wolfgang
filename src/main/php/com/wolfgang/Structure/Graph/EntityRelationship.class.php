<?php

namespace Wolfgang\Structure\Graph;

use Wolfgang\Interfaces\Structure\Graph\IEntityRelationship;
use Wolfgang\Interfaces\IEntity;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class EntityRelationship extends Component implements IEntityRelationship {
	/**
	 *
	 * @var IEntity
	 */
	public $e1;
	
	/**
	 *
	 * @var IEntity
	 */
	public $e2;
	
	/**
	 *
	 * @param IEntity $e1
	 * @param IEntity $e2
	 */
	public function __construct ( IEntity $e1, IEntity $e2 ) {
		parent::__construct();
		
		$this->e1 = $e1;
		$this->e2 = $e2;
	}
}
