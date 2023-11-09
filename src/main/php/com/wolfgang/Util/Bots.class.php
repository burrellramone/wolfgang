<?php

namespace Wolfgang\Util;

use Wolfgang\Encoding\JSON;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Bots extends Component {

	/**
	 *
	 * @return mixed
	 */
	public static function getBots ( ) {
		return JSON::decode( Filesystem::getContents( BOTS_JSON_FILE ) );
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isBot ( ): bool {
		foreach ( self::getBots() as $bot ) {
			if ( preg_match( "/{$bot->pattern}/", $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 *
	 * @return bool
	 */
	public static function isAllowedBot ( ): bool {
		if ( ! self::isBot() ) {
			return false;
		}

		foreach ( self::getBots() as $bot ) {
			if ( @$bot->allow && preg_match( "/{$bot->pattern}/", $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
				return true;
			}
		}

		return false;
	}
}
