<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\ICommand;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Command extends Component implements ICommand {
	
	/**
	 * A string representation of this command
	 *
	 * @var string
	 */
	protected $command;
	
	/**
	 * A list of parameters that were passes as arguments in this command
	 *
	 * @var array
	 */
	protected $parameters;
	
	/**
	 *
	 * @param string
	 */
	public function __construct ( string $command ) {
		parent::__construct();
		
		$this->setCommand( $command );
	}
	
	/**
	 * Sets the string representation of this command instance
	 *
	 * @param string
	 */
	private function setCommand ( string $command ) {
		if ( empty( $command ) ) {
			throw new InvalidArgumentException( "Command not provided" );
		}
		
		$this->command = $command;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getCommand ( ): string {
		return $this->command;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ICommand::getParameters()
	 */
	public function getParameters ( ): array {
		return $this->parameters;
	}
	
	public function __toString ( ) {
		return $this->getCommand();
	}
}