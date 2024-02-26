<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\IllegalStateException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @see https://misc.flogisoft.com/bash/tip_colors_and_formatting
 * @since Version 0.1.0
 */
final class CliWriter extends Component {

	public static $COLOR_RED = '31m';
	public static $COLOR_BLUE = '34m';

	public static function info( string $message ){
		if(!$message){
			return;
		}
		
		echo "\033[" . self::$COLOR_BLUE . "{$message}\e[0m\n";
	}
	public static function error( string $message ){
		if(!$message){
			return;
		}
		
		echo "\033[" . self::$COLOR_RED . "{$message}\e[0m\n";
	}
}