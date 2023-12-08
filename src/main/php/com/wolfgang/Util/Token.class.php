<?php

namespace Wolfgang\Util;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Token extends Component {
	
	/**
	 *
	 * @return string
	 */
	public static function generate ( ): string {
		return hash( 'sha256', time() ) . '.' . md5( time() );
	}
}
