<?php

namespace Traits;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
trait TController {

	protected $application;

	abstract protected function getApplication();

	/**
	 * @param string $error
	 */
	protected function addError(string $error):void{
		$this->getApplication()->addError($error);
	}

	/**
	 * @param string $error
	 */
	protected function addNotice(string $notice):void{
		$this->getApplication()->addNotice($notice);
	}

	/**
	 * @see EventManager::create()
	 * @see Event::fire()
	 */
	protected function fireEvent(){
		$e = call_user_func_array( array ( 'Wolfgang\Event\Manager', 'create' ), func_get_args() );
		
		$this->getApplication()->getEventDispatcher()->dispatch( $e );
	}
}
