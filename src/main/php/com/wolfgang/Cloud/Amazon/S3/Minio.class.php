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
}
