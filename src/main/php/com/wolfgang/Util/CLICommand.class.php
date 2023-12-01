<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\IllegalStateException;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class CLICommand extends Command {
	
	/**
	 * The name of the program to be executed
	 *
	 * @var string
	 */
	private $program;
	
	/**
	 *
	 * @var array
	 */
	private $output;
	
	/**
	 *
	 * @var int
	 */
	private $exit_code;
	
	/**
	 *
	 * @var string
	 */
	public function __construct ( string $command ) {
		parent::__construct( $command );
	}
	
	public function exec ( ) {
		if ( ! self::programExists( $this->getProgram() ) ) {
			throw new IllegalStateException( "Unable to execute command '{$this->getCommand()}'. Program '{$this->program}' does not exist." );
		}
		
		exec( ( string ) $this, $this->output, $this->exit_code );
		return $this->exit_code;
	}
	
	/**
	 *
	 * @return array|null
	 */
	public function getOutput ( ): ?array {
		return $this->output;
	}
	
	/**
	 *
	 * @return int|null
	 */
	public function getExitCode ( ): ?int {
		return $this->exit_code;
	}
	
	/**
	 * Gets a string representation of this command
	 *
	 * @return string|null
	 */
	public function getCommand ( ): string {
		return $this->command;
	}
	
	/**
	 * Determines whether or not a specific program exists
	 *
	 * @param string $program The program to check if exists
	 * @return bool True if the program exists, false otherwise
	 */
	public static function programExists ( string $program ): bool {
		if ( file_exists( "/bin/{$program}" ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Gets the name of the program used in this command
	 *
	 * @return string The name of the program used in this command
	 */
	public function getProgram ( ): string {
		return $this->program;
	}
}