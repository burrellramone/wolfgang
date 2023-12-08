<?php

namespace Wolfgang\MQ\AMQP;

// PHP
use AMQPChannel;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Exchange extends Component {
	/**
	 *
	 * @var \AMQPExchange
	 */
	private $exchange;
	
	/**
	 *
	 * @param AMQPChannel $channel
	 */
	public function __construct ( Channel $channel ) {
		parent::__construct();
		
		$this->exchange = new \AMQPExchange( $channel->getChannel() );
	}
	
	/**
	 *
	 * @param string $message
	 * @param string $routing_key
	 * @param string $flags
	 * @param array $headers
	 */
	public function publish ( $message, $routing_key = NULL, $flags = NULL, array $headers = array()) {
		return $this->exchange->publish( serialize( $message ), $routing_key, $flags, $headers );
	}
}