<?php

namespace Wolfgang\Util;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Arrays extends Component {
	public static function filter ( ) {
		return call_user_func_array( 'array_filter', func_get_args() );
	}
	public static function values ( ) {
		return call_user_func_array( 'array_values', func_get_args() );
	}
}
