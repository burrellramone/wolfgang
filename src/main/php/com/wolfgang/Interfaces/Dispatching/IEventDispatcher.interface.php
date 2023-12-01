<?php

namespace Wolfgang\Interfaces\Dispatching;

use Wolfgang\Interfaces\Event\IEvent;
use Wolfgang\Interfaces\Event\IEventListener;

/**
 *
 * @package Wolfgang\Interfaces\Dispatching
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
interface IEventDispatcher {

	/**
	 *
	 * @param IEvent $event
	 */
	public function dispatch ( IEvent $event );

	/**
	 *
	 * @param IEventListener $listener
	 */
	public function addEventListener ( IEventListener $listener );

	/**
	 *
	 * @return array
	 */
	public function getListeners ( ): array;
}