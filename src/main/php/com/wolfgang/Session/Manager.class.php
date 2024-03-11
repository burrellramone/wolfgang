<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Session\ISession;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Manager extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var Session
	 */
	protected $session;


	/**
	 * @param string $kind
	 * @param array $options
	 * @return ISession
	 */
	public function createSession(string $kind, array $options = array()){
		$options['kind'] = $kind;
		
		$this->session = Session::create($options);

		return $this->session;
	} 

	/**
	 * @return ISession
	 */
	public function getSession (  ): ?ISession {
		if( $this->session ) {
			return $this->session;
		}

		return null;
	}
}