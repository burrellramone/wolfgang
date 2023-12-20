<?php

namespace Wolfgang\Message;

use Wolfgang\Component as WolfgangComponent;
use Wolfgang\Interfaces\Message\IMessage;
use Wolfgang\Traits\Message\TMessage;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Message extends WolfgangComponent implements IMessage {
	use TMessage;
}
