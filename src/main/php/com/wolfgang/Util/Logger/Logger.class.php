<?php

namespace Wolfgang\Util\Logger;

use Wolfgang\Util\Component;
use Wolfgang\Interfaces\Logger\ILogger;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 1.0
 */

abstract class Logger extends Component implements ILogger {

	/**
	 *
	 * @param string $name
	 * @return ILogger
	 */
	public static function getLogger ( string $name = null ): ILogger {
		return FileLogger::getLogger($name);	
	}
}