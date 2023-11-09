<?php

namespace Wolfgang\Util\Logger;

use Wolfgang\Interfaces\Logger\ILogger;
use Wolfgang\Interfaces\Logger\IHTTPLogger;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0
 */
final class HTTPLogger extends Logger implements IHTTPLogger {
	
	/**
	 *
	 * @var array
	 */
	protected $instances;
	
	/**
	 *
	 * @param string $name
	 * @return ILogger
	 */
	public static function getLogger ( string $name = null): ILogger {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::debug()
	 */
	public function debug ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::critical()
	 */
	public function critical ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::alert()
	 */
	public function alert ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::log()
	 */
	public function log ( $level, $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::emergency()
	 */
	public function emergency ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::warning()
	 */
	public function warning ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::error()
	 */
	public function error ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::notice()
	 */
	public function notice ( $message, array $context = []) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::info()
	 */
	public function info ( $message, array $context = []) {
	}
}