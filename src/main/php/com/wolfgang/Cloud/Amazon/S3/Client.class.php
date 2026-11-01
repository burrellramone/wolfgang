<?php

namespace Wolfgang\Cloud\Amazon\S3;

use Wolfgang\Component;
use Aws\S3\S3Client;
use Wolfgang\Exceptions\Exception as CoreException;
use Wolfgang\Interfaces\IS3ClientConfig;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.0
 */
final class Client extends Component {
	
	/**
	 *
	 * @var S3Client
	 */
	protected $s3_client;
	
	/**
	 *
	 * @param IS3ClientConfig $config
	 * @throws CoreException
	 */
	public function __construct ( IS3ClientConfig $config ) {
		parent::__construct();
		
		try {
			$this->s3_client = new S3Client( [ 
				'region' => 'us-east-1',
				'version' => 'latest',
				'scheme'  => $config->getProtocol(),
				'endpoint' => $config->getProtocol() . '://' . $config->getHostname() . ':' . $config->getPort(),
				'use_path_style_endpoint' => true,
				'http' => [
					'verify' => (bool) $config->getHttpVerify(),
				],
				'credentials' => [ 
					'key' => $config->getAccessKey(),
					'secret' => $config->getSecretKey(),
				]
			] );
			
			$this->listBuckets();
		} catch ( \Exception $e ) {
			throw new CoreException( "Unable to connect to S3 instance", 0, $e );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see Object::__call()
	 */
	public function __call ( $method, $arguments ) {
		// @formatter:off
		return call_user_func_array([$this->s3_client, $method], $arguments);
		// @formatter:on
	}
}
