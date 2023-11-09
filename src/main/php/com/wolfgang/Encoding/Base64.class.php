<?php

namespace Wolfgang\Encoding;

use Wolfgang\Interfaces\IEncoder;

/**
 *
 * @package Wolfgang\Encoding
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @uses Wolfgang\Interfaces\IEncoder
 * @uses Wolfgang\Component
 * @since Version 1.0.0
 */
final class Base64 extends Component implements IEncoder {

	/**
	 *
	 * @param string $string
	 */
	public static function encode ( $data ): string {
		return base64_encode( $data );
	}

	/**
	 *
	 * @param string $data
	 * @param bool $strict
	 * @return string
	 */
	public static function decode ( string $data, bool $strict = false) {
		return base64_decode( $data, $strict );
	}

	/**
	 *
	 * @param string $data
	 * @return bool
	 */
	public static function isEncoded ( string $data ): bool {
		if ( ! $data ) {
			return false;
		}

		return self::decode( $data, true ) !== false;
	}
}
