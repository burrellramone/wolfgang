<?php
namespace Wolfgang\Traits;

use Wolfgang\Interfaces\IMarshallable;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TMarshaller {
	
	/**
	 * @param mixed
	 * @return mixed
	 */
	public static function recursiveMarshall ( mixed $data ) : mixed {
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
				foreach ( $data as $key => &$value ) {
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
