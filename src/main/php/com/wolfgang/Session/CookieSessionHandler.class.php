<?php

namespace Wolfgang\Session;

use Wolfgang\Util\Cookie;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\Session\Exception as SessionException;
use Wolfgang\Interfaces\Session\ISessionHandler;
use Wolfgang\Application\Application;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class CookieSessionHandler extends Component implements ISessionHandler {
	
	/**
	 * @var string The (sub)domain that the cookie is available to.
	 */
	private $domain;

	/**
	 * @param string $domain The (sub)domain that the cookies that are written is available to.
	 */
	public function __construct ( string $domain ) {
		parent::__construct();

		$this->setDomain($domain);
	}

	/**
	 * @param string $domain
	 */
	private function setDomain( string $domain ):void{
		$this->domain = $domain;
	}

	/**
	 * @return string
	 */
	public function getDomain():string {
		return $this->domain;
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
		
		if ( ! Cookie::write( $session_id, $session_data, time() + YEAR_IN_SECONDS, '/', $this->getDomain() ) ) {
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