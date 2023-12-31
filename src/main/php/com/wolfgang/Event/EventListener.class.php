<?php

namespace Wolfgang\Event;

use Wolfgang\Interfaces\Event\IEventListener;
use Wolfgang\Interfaces\Event\IEvent;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @version 1.0.0
 * @since Version 0.1.0
 */
abstract class EventListener extends Component implements IEventListener {

	/**
	 *
	 * @var bool
	 */
	protected $enabled = true;

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Event\IEventListener::handler()
	 */
	public function handle ( IEvent $event ) {
		if ( method_exists( $this, $event->getName() ) ) {
			$this->{$event->getName()}( $event );
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Event\IEventListener::isEnabled()
	 */
	public function isEnabled ( ): bool {
		return $this->enabled;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Event\IEventListener::getName()
	 */
	public function getName ( ): string {
		if ( ! $this->enabled ) {
			return get_class( $this );
		}

		return $this->name;
	}
}
