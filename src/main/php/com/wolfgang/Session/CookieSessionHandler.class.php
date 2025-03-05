<?php

namespace Wolfgang\Session;

use Wolfgang\Util\Cookie;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\Session\Exception as SessionException;
use Wolfgang\Interfaces\Session\ISessionHandler;
use Wolfgang\Traits\TSessionHandler;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class CookieSessionHandler extends Component implements ISessionHandler {
	use TSessionHandler;

	/**
	 * @var string The (sub)domain that the cookie is available to.
	 */
	private $domain;

	/**
	 * @param string $domain The (sub)domain that the cookies that are written is available to.
	 * @param string $expires The number of seconds after the session cookie is set/session is created that it should expire.
	 */
	public function __construct ( string $domain, int $expires = 0) {
		parent::__construct();

		$this->setDomain($domain);
		$this->setExpires($expires);
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
	 * @see https://www.php.net/manual/en/sessionhandlerinterface.destroy.php
	 * @param string $session_id
	 */
	public function destroy ( string $session_id ): bool {
		session_destroy();

		$_SESSION = [];

		return Cookie::write( $session_id, 'deleted', -1, '/', $this->getDomain() );
	}
	
	/**
	 *
	 * @param string $session_id
	 * @return string|false
	 */
	public function read ( $session_id ): string|false {
		//Must return at least empty string else we'll get error "Warning:  session_start(): Failed to read session data: user (path: /var/lib/php/sessions)"
		$session_data = '';

		if ( isset( $_COOKIE[ $session_id ] ) ) {
			$session_data = Cookie::read( $session_id );
		}
		
		return $session_data;
	}
	
	/**
	 * @see http://php.net/setcookie
	 * @param string $session_id
	 * @param string $session_data
	 * @throws SessionException
	 * @return bool
	 */
	public function write ( string $session_id, string $session_data ):bool {
		if ( empty( $session_id ) ) {
			throw new InvalidArgumentException( "Session id must be provided" );
		} else if ( php_sapi_name() == 'cli' ) {
			return true;
		} else if ( headers_sent() ) {
			return true;
		}

		if ( ! Cookie::write( $session_id, $session_data, $this->getExpires(), '/', $this->getDomain() ) ) {
			throw new SessionException( "Failed to write session data for session id {$session_id}" );
		}
		
		return true;
	}
	
	public function gc (int $maxlifetime ): int|false {
		return true;
	}
	
	/**
	 * @see https://www.php.net/manual/en/sessionhandler.open.php
	 * @see https://www.php.net/manual/en/sessionhandlerinterface.open.php
	 */
	public function open ( $save_path, $session_name ) :bool{
		return true;
	}
	
	public function close ( ) :bool{
		return true;
	}
}