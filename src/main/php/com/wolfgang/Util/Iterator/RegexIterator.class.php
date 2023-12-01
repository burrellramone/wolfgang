<?php

namespace Wolfgang\Util\Iterator;

use Wolfgang\Util\Component as UtilComponent;
use Wolfgang\Interfaces\IIterator;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @package Wolfgang\Util\Iterator
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @version 1.0.0
 * @since Version 1.0.0
 */
final class RegexIterator extends UtilComponent implements IIterator {

	public static function create ( string $regex, int $type ) {
		$regex_iterator = null;
		$args = func_get_args();

		switch ( $type ) {
			case IIterator::TYPE_RECURSIVE_DIRECTORY_ITERATOR :
				$directory = new \RecursiveDirectoryIterator( $args[ 2 ] );
				$iterator = new \RecursiveIteratorIterator( $directory );
				$regex_iterator = new \RegexIterator( $iterator, $regex );
				break;
			default :
				throw new InvalidArgumentException( "Invalid RegexIterator type provided" );
				break;
		}

		return $regex_iterator;
	}
}