<?php

namespace Wolfgang\Cloud\Amazon\S3;

use Wolfgang\Component;
use Wolfgang\Exceptions\Exception as CoreException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Interfaces\IS3ClientConfig;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 1.0.5
 */
final class SeaweedFs extends Component {

	/**
	 *
	 * @var Client
	 */
	private $client;

	/**
	 *
	 * @param IS3ClientConfig $config
	 * @throws CoreException
	 */
	public function __construct (IS3ClientConfig $config) {
		parent::__construct();
		
		$this->client = new Client( $config );
	}

	/**
	 * Transforms a string into an appropriate bucket name
	 * 
	 * @param string $bucket The string to transform
	 * @return string The string transformed into an appropriate bucket name
	 */
	public static function bucketize( string $bucket ): string {
		if(!$bucket){
			throw new InvalidArgumentException("Bucket string not provided");
		}

		$bucket = preg_replace("/[^\w]+/", '', $bucket);
		$bucket = strtolower($bucket);
		
		return $bucket;
	}

	/**
	 * Checks if a bucket exists
	 *
	 * @param string $bucket
	 */
	public function bucketExists(string $bucket): bool {
		$result = $this->listBuckets();

		foreach($result as $key => $r){
			if($key == 'Buckets'){
				foreach($r as $bucket_array){
					if($bucket_array['Name'] == $bucket){
						return true;
					}
				}
			}
		}
		
		return false;
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
