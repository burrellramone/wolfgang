<?php

namespace Wolfgang\Date;

use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @package Wolfgang\Date
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
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
	public function __construct ( $time = "now" ) {
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
