<?php

namespace Wolfgang\Cloud\Amazon\S3;

use Wolfgang\Component;
use Wolfgang\Exceptions\Exception as CoreException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
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
	 * @var bool 
	 */
	private $http_verify;
	
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
	 * @param bool $httpVerify
	 * @throws CoreException
	 */
	public function __construct ( int $id, string $protocol, string $hostname, string $port, bool $httpVerify = true ) {
		parent::__construct();
		
		$this->id = $id;
		$this->protocol = $protocol;
		$this->hostname = $hostname;
		$this->port = $port;
		$this->http_verify = $httpVerify;
		
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
	 * @return bool
	 */
	public function getHttpVerify(): bool {
		return $this->http_verify;
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
