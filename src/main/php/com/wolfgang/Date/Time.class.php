<?php

namespace Wolfgang\Date;

use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Time extends Component {
	const FORMAT = 'H:i';

	/**
	 *
	 * @var \DateTime
	 */
	private $datetime;

	/**
	 *
	 * @param string $time
	 * @throws InvalidArgumentException
	 */
	public function __construct ( string $time = "now" ) {
		parent::__construct();

		$unix_time = strtotime( $time );

		if ( $unix_time < 0 ) {
			throw new InvalidArgumentException( "Invalid time provided" );
		}

		$this->datetime = new \DateTime( date( 'Y-m-d H:i:s', $unix_time ) );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		return $this->datetime->format( self::FORMAT );
	}
}
