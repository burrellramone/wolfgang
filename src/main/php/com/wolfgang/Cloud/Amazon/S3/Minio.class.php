<?php

namespace Wolfgang\Cloud\Amazon\S3;

use Wolfgang\Component;
use Wolfgang\Exceptions\Exception as CoreException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Config\Minio as MinioConfig;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Minio extends Component {

	/**
	 *
	 * @var array
	 */
	private static $tenants = [ ];

	/**
	 *
	 * @param int $id
	 * @return MinioTenant|NULL
	 */
	public static function getTenantById ( int $id ): ?MinioTenant {
		if ( array_key_exists( $id, self::$tenants ) ) {
			return self::$tenants[ $id ];
		}

		return null;
	}

	public static function createTenantFromArray( array $tenant ):MinioTenant{
		return Minio::createTenant( $tenant['id'], $tenant['protocol'], $tenant['host'], $tenant['port'] );
	}

	/**
	 *
	 * @param int $id
	 * @param string $protocol
	 * @param string $hostname
	 * @param string $port
	 * @throws CoreException
	 * @return \Wolfgang\Cloud\Amazon\S3\MinioTenant
	 */
	public static function createTenant ( int $id, string $protocol, string $hostname, string $port ) {
		if ( self::getTenantById( $id ) ) {
			throw new CoreException( "Minio tenant with id '{$id}' has already been instantiated" );
		}

		$tenant = new MinioTenant( $id, $protocol, $hostname, $port );
		self::$tenants[ $id ] = &$tenant;
		return $tenant;
	}

	/**
	 * @param string $bucket
	 */
	public static function bucketize( string $bucket ): string {
		if(!$bucket){
			throw new InvalidArgumentException("Bucket string not provided");
		}

		$bucket = preg_replace("/[^\w]/", '', $bucket);
		$bucket = strtolower($bucket);
		
		return $bucket;
	}

	/**
	 * Checks if a bucket exists across all available tenants
	 */
	public static function bucketExists( string $bucket ): bool {
		$tenants = MinioConfig::getAvailableTenants();

		foreach($tenants as $tenant){
			$s3_minio_tenant = self::getTenantById( $tenant['id'] );

			if ( ! $s3_minio_tenant ) {
				$s3_minio_tenant = Minio::createTenantFromArray( $tenant );
			}

			$result = $s3_minio_tenant->listBuckets();

			foreach($result as $key => $r){
				if($key == 'Buckets'){
					foreach($r as $bucket_array){
						if($bucket_array['Name'] == $bucket){
							return true;
						}
					}
				}
			}
		}

		return false;
	}
}