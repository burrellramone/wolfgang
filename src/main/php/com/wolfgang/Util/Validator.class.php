<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Validator extends Component {

	/**
	 *
	 * @see http://php.net/manual/en/function.filter-var.php
	 * @param string $url
	 * @param mixed $options
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public static function isURL ( $url, $options = null): bool {
		if ( ! is_string( $url ) ) {
			return false;
		}

		return filter_var( $url, FILTER_VALIDATE_URL, $options );
	}
}
