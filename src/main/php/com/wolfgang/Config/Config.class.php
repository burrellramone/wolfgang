<?php

namespace Wolfgang\Config;

use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Util\Filesystem;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Exceptions\InvalidStateException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Config extends Component {
	/**
	 * Whether or not the configurations for this class has been loaded
	 *
	 * @var boolean
	 */
	protected static $is_loaded = false;
	/**
	 *
	 * @var array
	 */
	protected static $configuration_groups = [ ];

	/**
	 *
	 * @var array
	 */
	protected static $configurations = array ();

	/**
	 *
	 * @access public
	 * @param string $name
	 */
	public static function get ( $name ) {
		$reflector = new \ReflectionClass( get_called_class() );
		$configuration_group = strtolower( $reflector->getShortName() );
		$application_environment = APPLICATION_ENVIRONMENT;

		if ( ! empty( $GLOBALS[ 'SUPER_APPLICATION_ENVIRONMENT' ] ) ) {
			$application_environment = $GLOBALS[ 'SUPER_APPLICATION_ENVIRONMENT' ];
		}

		if ( empty( self::$configurations[ $configuration_group ] ) ) {
			self::load( IContext::ENVIRONMENT_DEFAULT, $configuration_group );
			self::load( $application_environment, $configuration_group );

			if ( empty( self::$configurations[ $configuration_group ] ) ) {
				throw new InvalidStateException( "Configuration group '{$configuration_group}' could not be loaded." );
			}
		}

		if ( ! empty( self::$configurations[ $configuration_group ] ) ) {
			return self::recursiveGet( explode( '.', $name ), self::$configurations[ $configuration_group ] );
		}
	}

	/**
	 *
	 * @return array
	 */
	public static function getAll ( ): array {
		self::$configurations = [ ];

		$reflector = new \ReflectionClass( get_called_class() );
		$configuration_group = strtolower( $reflector->getShortName() );
		$application_environment = APPLICATION_ENVIRONMENT;

		if ( ! empty( $GLOBALS[ 'SUPER_APPLICATION_ENVIRONMENT' ] ) ) {
			$application_environment = $GLOBALS[ 'SUPER_APPLICATION_ENVIRONMENT' ];
		}

		self::load( IContext::ENVIRONMENT_DEFAULT, $configuration_group );
		self::load( $application_environment, $configuration_group );

		if ( ! array_key_exists( $configuration_group, self::$configurations ) ) {
			throw new InvalidStateException( "Configuration group '{$configuration_group}' could not be loaded." );
		}

		return self::$configurations[ $configuration_group ];
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::__call()
	 */
	public function __call ( string $method, array $arguments ) {
		$matches = array ();

		if ( preg_match( "/(get)([A-Za-z0-9]+)$/", $method, $matches ) ) {

			$property = preg_replace( "/^(get)/", "", $method );
			$property = preg_replace( "/(?<=[a-z])([A-Z])/", "_$1", $property );
			$property = strtolower( $property );

			return self::get( $property );
		}
	}

	/**
	 *
	 * @access private
	 * @param string $environment
	 * @param string $configuration_group
	 * @return void
	 */
	private static function load ( $environment, $configuration_group = null): void {
		if ( $configuration_group !== null ) {
			self::loadConfigurationGroup( $environment, $configuration_group );
			return;
		}

		$config_groups = self::getConfigurationGroups();

		foreach ( $config_groups as $config_group ) {
			self::loadConfigurationGroup( $environment, $config_group );
		}
	}

	/**
	 *
	 * @param string $environment
	 * @param string $configuration_group
	 * @throws InvalidStateException
	 */
	private static function loadConfigurationGroup ( string $environment, string $configuration_group ): void {
		if ( ! file_exists( CONFIGURATION_DIRECTORY ) ) {
			throw new InvalidStateException( "Configuration directory '" . CONFIGURATION_DIRECTORY . "' does not exist." );
		} else if ( !$environment ) {
		    throw new \InvalidArgumentException("The environment variable is not set.");
		}

		$filepath = CONFIGURATION_DIRECTORY . "{$environment}/{$configuration_group}.ini";
		$configurations = [ ];

		if ( file_exists( $filepath ) ) {
			$configurations = parse_ini_file( $filepath, true );
		}

		if ( ! empty( $configurations ) ) {
			if ( ! empty( self::$configurations[ $configuration_group ] ) ) {
				foreach ( $configurations as $key => $value ) {
				    if ( is_array($value) ) {
				        foreach ($value as $key2 => $value2) {
				            if(is_array($value2)) {
				                foreach ($value2 as $key3 => $value3) {
				                    self::$configurations[ $configuration_group ][ $key ][$key2][$key3] = $value3;
				                }
			                } else {
				                self::$configurations[ $configuration_group ][ $key ][$key2] = $value2;
			                }
				        }
				    } else {
				        self::$configurations[ $configuration_group ][ $key ] = $value;
				    }
				}
			} else {
				self::$configurations[ $configuration_group ] = $configurations;
			}
		}
	}

	/**
	 *
	 * @throws InvalidStateException
	 * @return array
	 */
	private static function getConfigurationGroups ( ): array {
		if ( ! file_exists( CONFIGURATION_DIRECTORY ) ) {
			throw new InvalidStateException( "Configuration directory '" . CONFIGURATION_DIRECTORY . "' does not exist" );
		}

		if ( empty( self::$configuration_groups ) ) {
			$ini_files = Filesystem::glob( CONFIGURATION_DIRECTORY . 'default/*.ini', GLOB_ERR );

			foreach ( $ini_files as $key => $ini_file ) {
				self::$configuration_groups[ $key ] = str_replace( '.ini', '', basename( $ini_file ) );
			}
		}

		return self::$configuration_groups;
	}

	/**
	 *
	 * @access private
	 * @param string $name_parts
	 * @param array $configurations
	 * @throws IllegalArgumentException
	 * @return string|array
	 */
	private static function recursiveGet ( $name_parts, array $configurations ) {
		if ( ! is_array( $configurations ) ) {
			throw new ComponentException( "Configuration is not an array" );
		} else if ( empty( $configurations ) ) {
			throw new InvalidArgumentException( "Configuration provided is empty" );
		}

		do {
			$name = array_shift( $name_parts );

			if ( ! array_key_exists( $name, $configurations ) ) {
				throw new IllegalArgumentException( "Configuration name '{$name}' does not exist" );
			}

			$configurations = $configurations[ $name ];
		} while ( ! empty( $name_parts ) );

		return $configurations;
	}
}
