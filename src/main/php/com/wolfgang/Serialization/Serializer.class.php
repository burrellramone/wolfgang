<?php

namespace Wolfgang\Serialization;

/**
 *
 * @package Wolfgang\Serialization
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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