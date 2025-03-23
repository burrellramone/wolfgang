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
	public static $COLOR_GREEN = '32m';
	public static $COLOR_BLUE = '34m';

	public static function clear(){
		echo "\e[H\e[J";
	}
	
	public static function info( string|array $message ){
		if(!$message){
			return;
		}

		if(is_array($message)){
			foreach($message as $m){
				self::info($m);
			}
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

	public static function success( string $message ){
		if(!$message){
			return;
		}
		
		echo "\033[" . self::$COLOR_GREEN . "{$message}\e[0m\n";
	}
}