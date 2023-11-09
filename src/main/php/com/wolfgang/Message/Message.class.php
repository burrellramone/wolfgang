<?php

namespace Wolfgang\Message;

use Wolfgang\Component as WolfgangComponent;
use Wolfgang\Interfaces\Message\IMessage;
use Wolfgang\Traits\Message\TMessage;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Message
 * @since Version 1.0.0
 */
abstract class Message extends WolfgangComponent implements IMessage {
	use TMessage;
}
