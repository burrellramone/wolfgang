<?php

namespace Wolfgang\Traits\Model;

use Wolfgang\Interfaces\IMarshaller;
use Wolfgang\Interfaces\IMarshallable as IModelMarshallable;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Util\DataTableMarshaller;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TModelListMarshallable {

	/**
	 *
	 * @param IMarshaller $marshaller
	 * @throws IllegalStateException
	 * @return \stdClass|array
	 */
	public function marshall ( IMarshaller $marshaller = null): array {
		if ( ! ($this->getUnitClassInstance() instanceof IModelMarshallable) ) {
			throw new IllegalStateException( "Unit class '{$this->getUnitClassInstance()}' does not implement interface 'Wolfgang\Interfaces\IMarshallable'" );
		}

		if ( $marshaller ) {
			return $marshaller->marshall( $this );
		}

		$items = array ();

		foreach ( $this as $object ) {
			$items[] = DataTableMarshaller::recursiveMarshall( $object );
		}

		return $items;
	}
}