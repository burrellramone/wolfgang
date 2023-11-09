<?php

namespace Wolfgang\Cloud\Amazon\S3;

use Wolfgang\Component;
use Wolfgang\Exceptions\Exception as CoreException;

/**
 *
 * @package Wolfgang\Cloud\Amazon\S3
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class MinioTenant extends Component {
	
	/**
	 *
	 * @var int
	 */
	private $id;
	
	/**
	 *
	 * @var string
	 */
	private $protocol;
	/**
	 *
	 * @var string
	 */
	private $hostname;
	/**
	 *
	 * @var string
	 */
	private $port;
	
	/**
	 *
	 * @var Client
	 */
	private $client;
	
	/**
	 *
	 * @param int $id
	 * @param string $protocol
	 * @param string $hostname
	 * @param string $port
	 * @throws CoreException
	 */
	public function __construct ( int $id, string $protocol, string $hostname, string $port ) {
		parent::__construct();
		
		$this->id = $id;
		$this->protocol = $protocol;
		$this->hostname = $hostname;
		$this->port = $port;
		
		$this->client = new Client( $this );
	}
	
	/**
	 *
	 * @return int
	 */
	public function getId ( ): int {
		return $this->id;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getProtocol ( ): string {
		return $this->protocol;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getHostname ( ): string {
		return $this->hostname;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getPort ( ): string {
		return $this->port;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see Object::__call()
	 */
	public function __call ( $method, $arguments ) {
		// @formatter:off
		return call_user_func_array([$this->client, $method], $arguments);
		// @formatter:on
	}
}
