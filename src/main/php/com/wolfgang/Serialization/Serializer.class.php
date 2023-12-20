<?php

namespace Wolfgang\Serialization;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Serializer extends Component {
	
	/**
	 *
	 * @param string $data
	 * @return string
	 */
	public static function serialize ( $data ): string {
		return igbinary_serialize( $data );
	}
	
	/**
	 *
	 * @param string $data
	 * @return mixed|null
	 */
	public static function unserialize ( $data ) {
		return igbinary_unserialize( $data );
	}
}