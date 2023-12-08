<?php

namespace Wolfgang\Dispatching;

use Wolfgang\Interfaces\Dispatching\IEventDispatcher;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Event\IEvent;
use Wolfgang\Interfaces\Event\IEventListener;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class EventDispatcher extends Component implements ISingleton , IEventDispatcher {

	/**
	 *
	 * @var EventDispatcher
	 */
	private static $instance;

	/**
	 *
	 * @var \Wolfgang\Interfaces\Application\IApplication
	 */
	protected $application;

	/**
	 *
	 * @var array
	 */
	protected $listeners = [ ];

	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * @return EventDispatcher
	 */
	public static function getInstance ( ) {
		if ( ! self::$instance ) {
			self::$instance = new EventDispatcher();
		}
		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Dispatching\IEventDispatcher::dispatch()
	 */
	public function dispatch ( IEvent $event ) {
		foreach ( $this->getListeners() as $key => $listener ) {
			if ( ! $event->isPropogating() ) {
				break;
			}

			$listener->handle( $event );
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Dispatching\IEventDispatcher::addEventListener()
	 */
	public function addEventListener ( IEventListener $listener ) {
		if ( ! empty( $this->listeners[ get_class( $listener ) ] ) ) {
			return;
		}

		$this->listeners[ get_class( $listener ) ] = $listener;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Dispatching\IEventDispatcher::getListeners()
	 */
	public function getListeners ( ): array {
		return $this->listeners;
	}
}