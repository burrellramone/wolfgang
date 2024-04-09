<?php

namespace Wolfgang\Date;

use Stringable;

//Wolfgang
use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Time extends Component implements Stringable {
	const FORMAT = 'h:i A';
	const FORMAT_24_HOUR = 'H:i';

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
	 * Formats the time according to a certain format
	 *
	 * @param string|null $format The format to use in formatting the time
	 * @return string The formatted time
	 */
	public function format(string $format = self::FORMAT):string {	
		return $this->datetime->format( $format ?? self::FORMAT );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		return $this->datetime->format( self::FORMAT_24_HOUR );
	}
}
