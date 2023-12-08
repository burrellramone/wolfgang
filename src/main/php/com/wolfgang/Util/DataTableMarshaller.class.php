<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\IMarshaller;
use Wolfgang\Interfaces\IMarshallable;
use Wolfgang\Interfaces\Model\IModelList;
use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class DataTableMarshaller extends Component implements IMarshaller , ISingleton {

	/**
	 *
	 * @var DataTableMarshaller
	 */
	private static $instance;

	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}

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

	public static function recursiveMarshall ( $data ) {
		if ( ! $data ) {
			return $data;
		}

		if ( $data ) {
			if ( is_object( $data ) ) {
				if ( ($data instanceof IMarshallable) ) {
					$digest = $data->marshall();

					if ( is_array( $digest ) || is_object( $digest ) ) {
						return self::recursiveMarshall( $digest );
					}

					return $digest;
				}

				foreach ( get_object_vars( $data ) as $property => $value ) {
					if ( ($data->$property instanceof IMarshallable) ) {
						$data->$property = $value->marshall();

						if ( is_array( $data->$property ) || is_object( $data->$property ) ) {
							$data->$property = self::recursiveMarshall( $data->$property );
						}
					} else if ( is_array( $data->$property ) ) {
						$data->$property = self::recursiveMarshall( $data->$property );
					}
				}
			} else if ( is_array( $data ) ) {

				foreach ( $data as &$value ) {

					if ( ($value instanceof IMarshallable) ) {
						$value = $value->marshall();

						if ( is_array( $value ) ) {
							foreach ( $value as &$value2 ) {
								if ( is_array( $value2 ) || is_object( $value2 ) ) {
									$value2 = self::recursiveMarshall( $value2 );
								}
							}
						}
					} else if ( is_array( $value ) ) {
						$value = self::recursiveMarshall( $value );
					}
				}
			}
		}

		return $data;
	}
}
