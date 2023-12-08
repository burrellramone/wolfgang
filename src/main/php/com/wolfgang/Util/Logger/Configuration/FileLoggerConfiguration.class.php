<?php

namespace Wolfgang\Util\Logger\Configuration;

use Wolfgang\Util\Logger\Component as LoggerComponent;
use Wolfgang\Config\App;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
final class FileLoggerConfiguration extends LoggerComponent {
	
	/**
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 *
	 * @var string
	 */
	protected $filepath;
	
	/**
	 *
	 * @param array $configuration
	 */
	public function __construct ( array $configuration ) {
		parent::__construct();
		
		$this->setName( $configuration[ 'name' ] );
		$this->setFilepath( App::get( 'directories.log_directory' ) . '/' . $configuration[ 'file' ] );
	}
	
	/**
	 *
	 * @param string $name
	 */
	private function setName ( string $name ) {
		$this->name = $name;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getName ( ): string {
		return $this->name;
	}
	
	/**
	 *
	 * @param string $filepath
	 */
	private function setFilePath ( string $filepath ) {
		$this->filepath = $filepath;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getFilepath ( ): string {
		return $this->filepath;
	}
}