<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Session\ISession;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @package Wolfgang\Session
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Manager extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var Session
	 */
	protected $session;
	
	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->session = Session::getInstance();
	}
	
	/**
	 *
	 * @return ISession
	 */
	public function getSession ( ): ISession {
		return $this->session;
	}
}