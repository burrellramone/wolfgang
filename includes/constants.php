<?php

/**
 * The version of this wolfgang instance
 *
 * @var string
 */
define( 'WOLFGANG_VERSION', "0.1.0" );

/**
 * The version of PHP the application is using
 *
 * @var string
 */
define( "_PHP_VERSION_", phpversion() );

/**
 *
 * @var string
 */
define( 'APPLICATION_ENVIRONMENT', getenv( 'APPLICATION_ENV' ) );

if ( ! defined( "DOCUMENT_ROOT" ) ) {
	/**
	 * The root of the application that Wolfgang is installed
	 *
	 * @var string
	 */
	define( "DOCUMENT_ROOT", realpath( dirname( __FILE__, 8 ) ) . "/" );
}

if ( ! defined( 'WOLFGANG_DIRECTORY' ) ) {

	/**
	 * The top-level directory of the Wolfgang framework
	 *
	 * @var string
	 */
	define( 'WOLFGANG_DIRECTORY', realpath( dirname( __FILE__, 2 ) ) . "/" );
}

if ( ! defined( "APPLICATION_ENVIRONMENT" ) ) {

	/**
	 *
	 * @var string
	 */
	define( 'APPLICATION_ENVIRONMENT', getenv( 'APPLICATION_ENV' ) );
}

/**
 * The top-level directory where the Wolfgang classes are stored relative to the application's
 * classes
 *
 * @var string
 */
define( 'WOLFGANG_CLASSES_DIR', WOLFGANG_DIRECTORY . 'src/main/php/com/wolfgang/' );

/**
 * The top-level directory where PHPUnit test classes are stored
 *
 * @var string
 */
define( 'WOLFGANG_TESTS_CLASSES_DIRECTORY', WOLFGANG_DIRECTORY . 'src/tests/php/com/wolfgang/' );

/**
 *
 * @var string
 */
define( 'WOLFGANG_RESOURCES_DIR', WOLFGANG_DIRECTORY . 'resources/' );

if ( ! defined( 'CONFIGURATION_DIRECTORY' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'CONFIGURATION_DIRECTORY', WOLFGANG_DIRECTORY . 'config/' );
}

/**
 * The top-level directory where the composer vendor packages are stored
 *
 * @var string
 */
define( 'WOLFGANG_VENDOR_DIRECTORY', WOLFGANG_DIRECTORY . 'vendor/' );

/**
 *
 * @var string
 */
define( 'BOTS_JSON_FILE', WOLFGANG_RESOURCES_DIR . "other/crawler-user-agents.json" );

/**
 *
 * @var int
 */
define( 'FILTER_VALIDATE_PHONE', 123456789 );

/**
 * V1 UUID used as the namespace under which all other UUIDs, V5, are generated
 *
 * @var string
 */
define( 'UUID_NAMESPACE', 'e4309058-5f59-11e8-8a69-abede464e6d6' );

/**
 *
 * @var string
 */
define( 'HOST_BOCCHERINI', 'boccherini' );

/**
 *
 * @var int
 */
define( 'MINUTE_IN_SECONDS', 60 );

/**
 *
 * @var int
 */
define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );

/**
 *
 * @var int
 */
define( 'DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS );

/**
 *
 * @var int
 */
define( 'WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS );

/**
 *
 * @var int
 */
define( 'MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS );

/**
 *
 * @var int
 */
define( 'YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS );

if ( ! defined( 'INTERVAL_MINUTE' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'INTERVAL_MINUTE', 'minute' );
}

if ( ! defined( 'INTERVAL_HOUR' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'INTERVAL_HOUR', 'hour' );
}

if ( ! defined( 'INTERVAL_DAY' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'INTERVAL_DAY', 'day' );
}

if ( ! defined( 'INTERVAL_WEEK' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'INTERVAL_WEEK', 'week' );
}

if ( ! defined( 'INTERVAL_MONTH' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'INTERVAL_MONTH', 'month' );
}

if ( ! defined( 'INTERVAL_YEAR' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'INTERVAL_YEAR', 'year' );
}

if ( ! defined( 'DATE_FORMAT' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'DATE_FORMAT', 'Y-m-d' );
}

if ( ! defined( 'DATE_TIME_FORMAT' ) ) {
	/**
	 *
	 * @var string
	 */
	define( 'DATE_TIME_FORMAT', 'Y-m-d H:i:s' );
}

