<?php

namespace Wolfgang\Util\Logger;

use Wolfgang\Interfaces\Logger\IUDPLogger;
use Wolfgang\Interfaces\Logger\ILogger;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
final class UDPLogger extends Logger implements IUDPLogger {
	
	/**
	 *
	 * @var array
	 */
	protected $instances;
	
	/**
	 *
	 * @param string|null $name
	 * @return ILogger
	 */
	public static function getLogger ( string|null $name = null): ILogger {
		$logger = null;

		return $logger;
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