<?php

namespace Wolfgang\MQ\AMQP;

// PHP
use AMQPChannel;

/**
 *
 * @package Wolfgang\MQ\AMQP
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
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
