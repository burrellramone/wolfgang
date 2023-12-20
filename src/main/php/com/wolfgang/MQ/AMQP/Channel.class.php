<?php

namespace Wolfgang\MQ\AMQP;

// PHP
use AMQPChannel;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Channel extends Component {
	/**
	 *
	 * @var AMQPChannel
	 */
	private $channel;
	
	/**
	 *
	 * @return AMQPChannel
	 */
	public function getChannel ( ): AMQPChannel {
		return $this->channel;
	}
}
