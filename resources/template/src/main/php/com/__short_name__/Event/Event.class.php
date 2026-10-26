<?php

namespace Event;

use Wolfgang\Interfaces\Event\IEvent;
use Interfaces\Model\IModel;
use Wolfgang\Event\Event as ComponentEvent;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
class Event extends ComponentEvent implements IEvent {

	/**
	 *
	 * @param IModel $subject
	 * @param string $action
	 * @param ?IModel $object
	 * @param string $preposition
	 */
	public function __construct ( IModel $subject, $action, ?IModel $object = null, $preposition = IEvent::PREPOSITION_ON ) {
		parent::__construct( $subject, $action, $object, $preposition );
	}
}