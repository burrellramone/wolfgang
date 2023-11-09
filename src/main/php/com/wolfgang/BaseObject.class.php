<?php

namespace Wolfgang;

// PHP
use ReflectionClass;

// Wolfgang
use Wolfgang\Exceptions\Exception;
use Wolfgang\Exceptions\NoSuchMethodException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
abstract class BaseObject {

	/**
	 *
	 * @var integer
	 */
	private static $hashCounter = 0;

	/**
	 *
	 * @var int
	 */
	private $hashCode;

	/**
	 *
	 * @var \ReflectionClass
	 */
	private $reflection;

	public function __construct ( ) {
		$this->init();
	}

	/**
	 */
	protected function init ( ) {
		$this->hashCode = self::$hashCounter ++;
	}

	/**
	 *
	 * @param string $method
	 * @param array $arguments
	 * @throws Exception
	 * @throws NoSuchMethodException
	 */
	public function __call ( string $method, array $arguments ) {
		$trace = debug_backtrace( null, 1 );

		if ( method_exists( get_called_class(), $method ) ) {
			throw new Exception( "Illegal access to member method '{$method}'. Called in {$trace[0]['file']} on line {$trace[0]['line']}." );
		} else {
			throw new NoSuchMethodException( "Method '{$method}' of the class '" . get_called_class() . "' does not exist. Called in {$trace[0]['file']} on line {$trace[0]['line']}." );
		}
	}

	/**
	 *
	 * @param string $name
	 * @param array $arguments
	 */
	public static function __callStatic ( $method, $arguments ) {
		if ( method_exists( get_called_class(), $method ) ) {
			throw new \Wolfgang\Exceptions\Exception( "Illegal access to static method '{$method}'" );
		} else {
			throw new \Wolfgang\Exceptions\NoSuchMethodException( "Method '{$method}' of the class '" . get_called_class() . "' does not exist" );
		}
	}

	public function __get ( $property ) {
		return @$this->{$property};
	}

	/**
	 *
	 * @param \ReflectionClass $reflector
	 * @return boolean
	 */
	public function extends ( $reflector ): bool {
		if ( is_subclass_of( get_called_class(), $reflector->getName() ) ) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return ReflectionClass
	 */
	public function getReflection ( ): ReflectionClass {
		if ( ! $this->reflection ) {
			$this->reflection = new ReflectionClass( $this );
		}

		return $this->reflection;
	}

	public function __destruct ( ) {
	}
}
