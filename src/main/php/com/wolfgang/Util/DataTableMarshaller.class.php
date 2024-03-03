<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\IMarshaller;
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Interfaces\Model\IModelList;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Traits\TMarshaller;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class DataTableMarshaller extends Component implements IMarshaller , ISingleton {
	use TMarshaller;
	
	/**
	 *
	 * @var DataTableMarshaller
	 */
	private static $instance;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * @return DataTableMarshaller
	 */
	public static function getInstance ( ): DataTableMarshaller {
		if ( ! self::$instance ) {
			self::$instance = new DataTableMarshaller();
		}
		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\IMarshaller::marshall()
	 */
	public function marshall ( IMarshallable $object ): \stdClass {
		$result = new \stdClass();

		$data = $object->marshall();
		$records_total = 1;
		$records_filtered = 1;

		if ( ! is_array( $data ) ) {
			$data = [ 
					$data
			];
		}

		$data = self::recursiveMarshall( $data );

		if ( ($object instanceof IModelList) ) {
			$records_total = $object->count();
			$records_filtered = $object->getTotalMatches();
		}

		$result->error = null;
		$result->data = $data;
		$result->draw = ! empty( $_REQUEST[ 'draw' ] ) ? $_REQUEST[ 'draw' ] : NULL;
		$result->recordsTotal = $records_total;
		$result->recordsFiltered = $records_filtered;

		return $result;
	}
}
