<?php

namespace Wolfgang\Encoding;

use Wolfgang\Exceptions\Encoding\JSON\Exception as JSONException;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0
 */
final class JSON extends Component {

	/**
	 *
	 * @throws JSONException
	 */
	private static function throwException ( ) {
		switch ( json_last_error() ) {
			case JSON_ERROR_DEPTH :
				throw new JSONException( ' - Maximum stack depth exceeded' );
				break;
			case JSON_ERROR_STATE_MISMATCH :
				throw new JSONException( ' - Underflow or the modes mismatch' );
				break;
			case JSON_ERROR_CTRL_CHAR :
				throw new JSONException( ' - Unexpected control character found' );
				break;
			case JSON_ERROR_SYNTAX :
				throw new JSONException( ' - Syntax error, malformed JSON' );
				break;
			case JSON_ERROR_UTF8 :
				throw new JSONException( ' - Malformed UTF-8 characters, possibly incorrectly encoded' );
				break;
		}
	}

	/**
	 *
	 * @see \json_encode()
	 * @param mixed $data
	 * @param boolean $pretty_print
	 */
	public static function encode ( $data, $pretty_print = true ) {
		$json = json_encode( $data, $pretty_print );

		if ( json_last_error() != JSON_ERROR_NONE )
			self::throwException();

		return $json;
	}

	/**
	 *
	 * @see \json_decode()
	 * @param string $string
	 * @param string $assoc
	 * @param number $depth
	 * @param number $options
	 * @return mixed
	 */
	public static function decode ( $string, $assoc = false, $depth = 512, $options = 0 ) {
		$item = json_decode( $string, $assoc, $depth, $options );

		if ( json_last_error() != JSON_ERROR_NONE ) {
			self::throwException();
		}

		return $item;
	}
}
