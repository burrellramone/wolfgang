<?php

namespace Wolfgang\Util;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Arrays extends Component {
	public static function filter ( ) {
		return call_user_func_array( 'array_filter', func_get_args() );
	}
	public static function values ( ) {
		return call_user_func_array( 'array_values', func_get_args() );
	}
}
