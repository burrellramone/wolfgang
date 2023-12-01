<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Exceptions\Exception;
use Wolfgang\BaseObject;
use Wolfgang\Model\Manager as ModelManager;
use Wolfgang\Interfaces\IModelDelta;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Util
 * @since Version 1.0.0
 */
final class ModelDelta extends Delta implements IModelDelta {

	/**
	 *
	 * @param BaseObject $subject
	 * @throws Exception
	 */
	public function __construct ( BaseObject $subject ) {
		if ( ! ($subject instanceof IModel) ) {
			throw new Exception( "Subject must implement Wolfgang\Interfaces\Model\IModel" );
		}

		parent::__construct( $subject );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();

		$modelManager = ModelManager::getInstance();
		$state = $modelManager->getPutState( $this->getSubject() );

		foreach ( $state as $property => $oldvalue ) {
			$newvalue = $this->getSubject()->{$property};

			if ( $newvalue != $oldvalue ) {
				$this->affected_properties[ $property ] = new DeltaChange( $property, $oldvalue, $newvalue );
			}
		}
	}
}
