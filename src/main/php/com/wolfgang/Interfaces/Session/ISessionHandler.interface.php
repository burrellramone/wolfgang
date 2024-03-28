<?php

namespace Wolfgang\Interfaces\Session;

use SessionHandlerInterface;

/**
 *
* @since Version 0.1.0
 */
interface ISessionHandler extends SessionHandlerInterface {
    
    /**
     * Sets the number of seconds after the session cookie is set/session is 
     * created that it should expire. For all web sessions, if 0 is set, the
     * session will expire when the brower closes. For CLI sessions, if 0 is
     * set, the session will expire once the command is executed.
     * 
	 * @param int
	 */
	public function setExpires(int $expires):void;

    /**
     * Gets the number of seconds after the session is created that it will expire
     * 
	 * @return int
	 */
	public function getExpires():int;
}