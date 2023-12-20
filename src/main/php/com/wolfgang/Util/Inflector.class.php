<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Exception;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Model\Model;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Inflector extends Component {

	/**
	 * Translates a table name to a fully qualified model class name.
	 *
	 * @throws IllegalArgumentException
	 * @param string $table_name
	 */
	public static function classify ( string $table_name ): ?string {
		if ( empty( $table_name ) ) {
			throw new IllegalArgumentException( "Table name not provided" );
		}

		$parts = preg_split( "/(_)/", $table_name );

		foreach ( $parts as &$part ) {
			$part = ucfirst( $part );
		}

		$class_name = implode( "", $parts );

		if ( in_array( $class_name, Model::$framework_class_names ) ) {
			$class_name = "Wolfgang\\Model\\" . $class_name;
		} else {
			$class_name = "Model\\" . $class_name;
		}

		return $class_name;
	}

	/**
	 * Converts the name of a model class to that of a table.
	 *
	 * @param string $class_name The name of the model class to convert into a table name. Must be a
	 *        fully qualified model class name
	 */
	public static function tablify ( string $class_name ): ?string {
		if ( strpos( $class_name, "Model\\" ) === false ) {
			throw new Exception( "Class name '{$class_name}' is not qualified fully" );
		}

		$table_name = '';
		$class_name = preg_replace( "~^(.*)Model\\\~i", "", $class_name );

		$tablename_parts = preg_split( "/([A-Z]{1})/", $class_name, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		foreach ( $tablename_parts as $key => $tablename_part ) {
			if ( $key != 0 && is_int( $key / 2 ) ) {
				$table_name .= "_";
			}

			$table_name .= $tablename_part;
		}

		return strtolower( $table_name );
	}

	/**
	 * Determine the getter method name for a provided class property name
	 *
	 * @param string $property
	 * @return string|NULL
	 */
	public static function getMethodify ( string $property ): ?string {
		list ( $getter_method, $setter_method ) = self::methodify( $property );

		return $getter_method;
	}

	/**
	 * Determine the setter method name for a provided class property name
	 *
	 * @param string $property
	 * @return string|NULL
	 */
	public static function setMethodify ( string $property ): ?string {
		list ( $getter_method, $setter_method ) = self::methodify( $property );

		return $setter_method;
	}

	/**
	 *
	 * @param string $property
	 * @return array|NULL
	 */
	public static function methodify ( string $property ): ?array {
		if ( ! $property ) {
			return null;
		}

		$method_name = str_replace( "_", " ", $property );
		$method_name = ucwords( $method_name );
		$method_name = str_replace( " ", "", $method_name );

		return array (
				"get{$method_name}",
				"set{$method_name}"
		);
	}
}
