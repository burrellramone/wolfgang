<?php

namespace Wolfgang\Util;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class ApplicationCommand extends Command {
	
	/**
	 */
	private $controller;
	
	/**
	 */
	private $action;
	
	/**
	 *
	 * @param string $command
	 */
	public function __construct ( string $command ) {
		parent::__construct( $command );
		
		foreach ( $this->parameters as $key => $value ) {
			if ( $key == 'controller' || $key == 'action' ) {
				$this->{$key} = $value;
			}
		}
	}
}
