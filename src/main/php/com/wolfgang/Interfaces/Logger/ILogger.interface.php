<?php

namespace Wolfgang\Interfaces\Logger;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface ILogger {
	/**
	 * System is unusable. A panic condition
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */

	const LEVEL_EMERGENCY = 0;

	/**
	 * Action must be taken immediately. A condition that should be corrected immediately, such as a
	 * corrupted system database.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_ALERT = 1;

	/**
	 * Critical conditions, such as hard device errors.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_CRITICAL = 2;

	/**
	 * Error conditions.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_ERROR = 3;

	/**
	 * Warning conditions.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_WARNING = 4;

	/**
	 * Normal but significant conditions. Conditions that are not error conditions, but that may
	 * require special handling.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_NOTICE = 5;

	/**
	 * Informational messages.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_INFO = 6;

	/**
	 * Debug-level messages. Messages that contain information normally of use only when debugging a
	 * program.
	 *
	 * @see https://tools.ietf.org/html/rfc5424
	 * @var integer
	 */
	const LEVEL_DEBUG = 7;

	/**
	 *
	 * @param string|null $name
	 * @return ILogger
	 */
	public static function getLogger ( string $name = null ): ILogger;

	/**
	 * System is unusable.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function emergency ( $message, array $context = array() );

	/**
	 * Action must be taken immediately. Example: Entire website down, database unavailable, etc.
	 * This should trigger the SMS alerts and wake you up.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function alert ( $message, array $context = array() );

	/**
	 * Critical conditions. Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function critical ( $message, array $context = array() );

	/**
	 * Runtime errors that do not require immediate action but should typically be logged and
	 * monitored.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function error ( $message, array $context = array() );

	/**
	 * Exceptional occurrences that are not errors. Example: Use of deprecated APIs, poor use of an
	 * API, undesirable things that are not necessarily wrong.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function warning ( $message, array $context = array() );

	/**
	 * Normal but significant events.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function notice ( $message, array $context = array() );

	/**
	 * Interesting events. Example: User logs in, SQL logs.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function info ( $message, array $context = array() );

	/**
	 * Detailed debug information.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function debug ( $message, array $context = array() );

	/**
	 * Logs with an arbitrary level.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log ( $level, $message, array $context = array() );
}
