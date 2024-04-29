<?php

namespace Wolfgang\Event;

use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Interfaces\Event\IEventListener;
use Wolfgang\Interfaces\Event\IEvent;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class EventListener extends Component implements IEventListener {

	/**
	 *
	 * @var bool
	 */
	protected bool $enabled = true;

	/**
	 *
	 * @var string
	 */
	protected ?string $name = null;

	protected ?IContext $context = null;

	/**
	 * Constructs a new instance of this event listener
	 *
	 * @param IContext $context
	 */
	public function __construct(IContext $context){
		parent::__construct();

		$this->context = $context;
	}

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

	/**
	 * @inheritDoc
	 * @return IContext
	 */
	public function getContext():IContext {
		return $this->context;
	}
}
