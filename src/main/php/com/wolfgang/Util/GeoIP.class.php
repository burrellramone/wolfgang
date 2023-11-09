<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Exception;

/**
 *
 * @package Components
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class GeoIP extends Component {
	
	public function __construct ( ) {
		parent::__construct();
	}
	
	/*
	 * geoip_asnum_by_name — Get the Autonomous System Numbers (ASN) geoip_continent_code_by_name —
	 * Get the two letter continent code geoip_country_code_by_name — Get the two letter country
	 * code geoip_country_code3_by_name — Get the three letter country code
	 */
	public static function getCountryName ( $hostname ) {
		self::validateHostname( $hostname );
		
		return geoip_country_name_by_name( $hostname );
	}
	
	/*
	 * geoip_database_info — Get GeoIP Database information geoip_db_avail — Determine if GeoIP
	 * Database is available geoip_db_filename — Returns the filename of the corresponding GeoIP
	 * Database geoip_db_get_all_info — Returns detailed information about all GeoIP database types
	 * geoip_domain_by_name — Get the second level domain name geoip_id_by_name — Get the Internet
	 * connection type geoip_isp_by_name — Get the Internet Service Provider (ISP) name
	 * geoip_netspeedcell_by_name — Get the Internet connection speed geoip_org_by_name — Get the
	 * organization name
	 */
	public static function getRecordByName ( $hostname ) {
		self::validateHostname( $hostname );
		
		return geoip_record_by_name( $hostname );
	}
	
	/*
	 * geoip_region_by_name — Get the country code and region geoip_region_name_by_code — Returns
	 * the region name for some country and region code combo geoip_setup_custom_directory — Set a
	 * custom directory for the GeoIP database. geoip_time_zone_by_country_and_region — Returns the
	 * time zone for some country and region code combo
	 */
	private static function validateHostname ( string $hostname ) {
		if ( preg_match( "/^(192\.168|127\.)/", $hostname ) ) {
			throw new Exception( "Invalid ip address '{$hostname}'" );
		}
	}
}
