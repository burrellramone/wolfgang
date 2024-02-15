<?php

namespace Wolfgang\Util\Logger;

use Wolfgang\Util\Component;
use Wolfgang\Interfaces\Logger\ILogger;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
* @since Version 0.1.0
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