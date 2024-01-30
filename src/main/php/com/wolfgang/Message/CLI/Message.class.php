<?php

namespace Wolfgang\Message\CLI;

use Wolfgang\Interfaces\Message\CLI\IMessage;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Message extends Component implements IMessage {
    public function __toString():string{
        return $this->getBody();
    }
}
