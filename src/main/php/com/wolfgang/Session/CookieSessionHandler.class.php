<?php

namespace Wolfgang\Session;

use Wolfgang\Util\Cookie;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\Session\Exception as SessionException;
use Wolfgang\Interfaces\Session\ISessionHandler;
use Wolfgang\Application\Application;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class CookieSessionHandler extends Component implements ISessionHandler {
	
	public function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * @param string $session_id
	 */
	public function destroy ( $session_id ): bool {
		return Cookie::write( $session_id, null, time() - 1, '/', $GLOBALS[ 'cookie_domain' ] );
	}
	
	/**
	 *
	 * @param string $session_id
	 */
	public function read ( $session_id ): string|false {
		if ( ! empty( $_COOKIE[ $session_id ] ) ) {
			return Cookie::read( $session_id );
		}
		return '';
	}
	
	/**
	 *
	 * @param string $session_id
	 * @param string $session_data
	 * @throws SessionException
	 * @return bool
	 */
	public function write ( $session_id, $session_data ):bool {
		if ( empty( $session_id ) ) {
			throw new IllegalArgumentException( "Session id must be provided" );
		} else if ( empty( $session_data ) ) {
			return true;
		} else if ( php_sapi_name() == 'cli' ) {
			return true;
		} else if ( \headers_sent() ) {
			return true;
		}
		
		if ( ! Cookie::write( $session_id, $session_data, time() + YEAR_IN_SECONDS, '/', Application::getInstance()->getContext()->getSkin()->getSkinDomain()->getDomain() ) ) {
			throw new SessionException( "Failed to write session data for session id {$session_id}" );
		}
		
		return true;
	}
	
	public function gc ( $maxlifetime ): int|false {
		return true;
	}
	
	public function open ( $save_path, $session_name ) :bool{
		return true;
	}
	
	public function close ( ) :bool{
		return true;
	}
}