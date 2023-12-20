<?php

namespace Wolfgang\Interfaces\Event;

use Wolfgang\Event\Event;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
interface IEventListener {

	/**
	 * Handles and event by calling a member method that matches the event's name
	 *
	 * @see IEvent::getName()
	 * @param Event $event
	 */
	public function handle ( IEvent $event );

	/**
	 * Determines whether or not this event listener is enabled
	 *
	 * @return bool
	 */
	public function isEnabled ( ): bool;

	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;
}