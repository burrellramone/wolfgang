<?php

namespace Wolfgang\Util;


//PGP
use ReflectionClass;
use ReflectionException;

//Wolfgang
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
		    
		    if (count($parts) == ONE) {
    		    try {
    		        $cn = $class_name ."\\{$parts[0]}";
    		        $instance = new ReflectionClass($cn);
    		        $class_name = $cn;
    		    } catch (ReflectionException $e) {
    		        
    		    }
		    } else {
		        try {
		            $cn = "Model\\" . "{$parts[0]}\\{$parts[0]}{$parts[1]}";
		            $instance = new ReflectionClass($cn);
		            $class_name = $cn;
		        } catch (ReflectionException $e) {
                    
		        }
		    }
			
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
		$class_name = preg_replace( ["~^(.*)Model\\\~i", "~\\\~"], "", $class_name );

		$table_name = preg_replace("/([A-Z]{1})/", "_$1", $class_name);
		$table_name = strtolower(substr($table_name, 1));
		$tablename_parts = explode("_", $table_name);
		
		if (count($tablename_parts) > ONE && ($tablename_parts[0] == $tablename_parts[1])) {
			unset($tablename_parts[0]);
			$tablename_parts = array_values($tablename_parts);
		}
		
		$table_name = implode("_", $tablename_parts);

		return $table_name;
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
