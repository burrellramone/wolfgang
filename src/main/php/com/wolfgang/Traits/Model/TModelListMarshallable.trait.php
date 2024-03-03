<?php
namespace Wolfgang\Traits\Model;

use Wolfgang\Util\DataTableMarshaller;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TModelListMarshallable {

	/**
	 *
	 * @return array
	 */
	public function marshall (): array {
		$items = array ();

		foreach ( $this as $object ) {
			$items[] = DataTableMarshaller::recursiveMarshall( $object );
		}

		return $items;
	}
}