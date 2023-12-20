<?php

namespace Wolfgang\Database;

use Wolfgang\Interfaces\Database\IResultSet;
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @abstract
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class ResultSet extends Component implements IResultSet {
	
	/**
	 *
	 * @var resource
	 */
	protected $result;
	
	/**
	 *
	 * @var int
	 */
	protected $position = 0;
	
	/**
	 *
	 * @param resource $result
	 */
	protected function __construct ( $result ) {
		parent::__construct();
		
		if ( ! is_object( $result ) ) {
			throw new InvalidArgumentException( "Result must be an object" );
		}
		
		$this->result = $result;
	}
}
