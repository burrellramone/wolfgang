<?php

namespace Wolfgang\MQ\AMQP;

// PHP
use AMQPConnection;

// Wolfgang
use Wolfgang\Config\Mq as MQConfig;
use Wolfgang\Exceptions\MQ\AMQP\Exception as AMQPException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Connection extends Component {
	
	/**
	 *
	 * @var \AMQPConnection
	 */
	protected $connection;
	
	public function __construct ( ) {
		parent::__construct();
	}
	
	protected function init ( ) {
		parent::init();
		
		$connect_options = array (
				"host" => MQConfig::get( 'host' ),
				"vhost" => MQConfig::get( 'vhost' ),
				"port" => MQConfig::get( 'port' ),
				"login" => MQConfig::get( 'login' ),
				"password" => MQConfig::get( 'password' )
		);
		
		$this->connection = new \AMQPConnection( $connect_options );
		$this->connection->connect();
		
		if ( ! $this->connection->isConnected() ) {
			throw new AMQPException( "Failed to establish connection with AMQP broker." );
		}
	}
}