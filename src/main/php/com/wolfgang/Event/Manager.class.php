<?php

namespace Wolfgang\Event;

use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\Event\IEvent;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Manager extends Component {

	/**
	 *
	 * @throws IllegalArgumentException
	 * @return IEvent
	 */
	public static function create ( ): IEvent {
		$args = func_get_args();

		$subject = @$args[ 0 ];
		$action = @$args[ 1 ];
		$object = @$args[ 2 ];
		$preposition = @$args[ 3 ];

		if ( ! $subject ) {
			throw new IllegalArgumentException( "Subject must be provided" );
		} else if ( ! $action ) {
			throw new IllegalArgumentException( "Action must be provided" );
		} else if ( ! $preposition ) {
			$preposition = IEvent::PREPOSITION_ON;
		}

		$e = new Event( $subject, $action, $object, $preposition );

		return $e;
	}
}