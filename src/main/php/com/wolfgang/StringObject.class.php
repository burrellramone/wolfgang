<?php

namespace Wolfgang;


/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class StringObject extends BaseObject{

    /**
     * 
     * @var string
     */
    public $value;

	/**
	 * 
	 * @param string $string
	 */
	public function __construct(string $string = "") {
	    parent::__construct();
	    
	    $this->value = $string;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function __toString():string {
	    return $this->value;
	}

	public function __destruct ( ) {
	    parent::__destruct();
	}
}
