<?php

namespace Wolfgang\Cloud\Amazon\S3;

use Wolfgang\Component;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Config\Minio as MinioConfig;
use Aws\S3\S3Client;
use Wolfgang\Exceptions\Exception as CoreException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Client extends Component {
	
	/**
	 *
	 * @var S3Client
	 */
	protected $s3_client;
	
	/**
	 *
	 * @param MinioTenant $tenant
	 * @throws CoreException
	 */
	public function __construct ( MinioTenant $tenant ) {
		parent::__construct();
		
		try {
			$this->s3_client = new S3Client( [ 
					'version' => 'latest',
					'region' => 'us-east-1',
					'endpoint' => $tenant->getProtocol() . '://' . $tenant->getHostname() . ':' . $tenant->getPort(),
					'use_path_style_endpoint' => true,
					'credentials' => [ 
							'key' => MinioConfig::get( 'access_key' ),
							'secret' => MinioConfig::get( 'secret_key' )
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
