<?php

namespace Wolfgang\Traits;

use Wolfgang\Interfaces\IExtensible;
use Wolfgang\Exceptions\IllegalStateException;

/**
 *
 * @package Wolfgang\Traits
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
trait TExtensible {

	/**
	 *
	 * @return string
	 */
	public static function getExtensionClass ( ): string {
		$class = get_called_class();

		$extension_class = "Wolfgang\Extension\\" . $class;

		return $extension_class;
	}

	/**
	 *
	 * @param string $method
	 * @param array $arguments
	 * @throws IllegalStateException
	 * @return mixed
	 */
	public function __call ( $method, $arguments ) {
		if ( ! ($this instanceof IExtensible) ) {
			throw new IllegalStateException( "This instance does not implement Wolfgang\Interfaces\IExtensible" );
		}

		$extension = $this->getExtension();

		if ( method_exists( $extension, $method ) ) {
			//@formatter:off
			return call_user_func_array( [ $extension, $method ], $arguments );
			// @formatter:on
		}

		if ( preg_match( "/^(set)([A-Z]{1})(.*)$/", $method ) ) {

			$backtrace = debug_backtrace( 0 | DEBUG_BACKTRACE_IGNORE_ARGS, 3 );
			$trace_class = new \ReflectionClass( $backtrace[ 1 ][ 'class' ] );

			if ( $trace_class->isInstance( $extension ) && method_exists( $this, $method ) ) {
				//@formatter:off
				return call_user_func_array( [ $this, $method ], $arguments);
				// @formatter:on
			}
		}

		return parent::__call( $method, $arguments );
	}

	/**
	 *
	 * @param string $method
	 * @param array $arguments
	 * @throws IllegalStateException
	 * @return mixed
	 */
	public static function __callstatic ( $method, $arguments ) {
		$called_class = get_called_class();
		$called_class_instance = new \ReflectionClass( $called_class );

		if ( ! ($called_class_instance->implementsInterface( 'Wolfgang\Interfaces\IExtensible' )) ) {
			throw new IllegalStateException( "Called class does not implement Wolfgang\Interfaces\IExtensible" );
		}

		$extension = self::getExtensionClass();

		if ( method_exists( $extension, $method ) ) {
			//@formatter:off
			return call_user_func_array( [ $extension, $method ], $arguments );
			// @formatter:on
		}

		if ( preg_match( "/^(set)([A-Z]{1})(.*)$/", $method ) ) {

			$backtrace = debug_backtrace( 0 | DEBUG_BACKTRACE_IGNORE_ARGS, 3 );
			$trace_class = new \ReflectionClass( $backtrace[ 1 ][ 'class' ] );

			if ( $trace_class->isInstance( $extension ) && method_exists( $called_class, $method ) ) {
				//@formatter:off
				return call_user_func_array( [ $called_class, $method ], $arguments);
				// @formatter:on
			}
		}

		return parent::__callstatic( $method, $arguments );
	}
}
