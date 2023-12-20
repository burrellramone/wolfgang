<?php

namespace Wolfgang\Event;

use Wolfgang\Interfaces\Event\IEvent;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Dispatching\EventDispatcher;

/**
 *
* @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class Event extends Component implements IEvent {

	/**
	 *
	 * @var string
	 */
	protected $action;

	/**
	 *
	 * @var IModel
	 */
	protected $subject;

	/**
	 *
	 * @var IModel
	 */
	protected $object;

	/**
	 *
	 * @var string
	 */
	protected $preposition;

	/**
	 *
	 * @var boolean
	 */
	protected $is_propogating = true;

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @param IModel $subject
	 * @param IModel $object
	 * @param string $action
	 * @param string $preposition
	 */
	public function __construct ( IModel $subject, $action, IModel $object = null, $preposition = IEvent::PREPOSITION_ON ) {
		$this->setSubject( $subject );

		if ( $object ) {
			$this->setObject( $object );
		}

		$this->setAction( $action );
		$this->setPreposition( $preposition );

		parent::__construct();
	}

	protected function init ( ) {
		parent::init();

		$reflector = new \ReflectionClass( get_class( $this->subject ) );

		$this->name = $this->getPreposition() . '' . $reflector->getShortName() . '' . ucfirst( $this->action );

		if ( $this->object ) {
			$reflector = new \ReflectionClass( get_class( $this->object ) );

			$this->name .= $reflector->getShortName();
		}
	}

	/**
	 *
	 * @param IModel $subject
	 */
	protected function setSubject ( IModel $subject ) {
		$this->subject = $subject;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent::getSubject()
	 */
	public function getSubject ( ): IModel {
		return $this->subject;
	}

	/**
	 *
	 * @param IModel $object
	 */
	protected function setObject ( IModel $object ) {
		$this->object = $object;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent::getObject()
	 */
	public function getObject ( ): IModel {
		return $this->object;

	}

	/**
	 *
	 * @param string $action
	 */
	protected function setAction ( $action ) {
		$this->action = $action;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent::getAction()
	 */
	public function getAction ( ): string {
		return $this->action;
	}

	/**
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent
	 * @param string $preposition
	 * @throws InvalidArgumentException
	 * @return void
	 */
	private function setPreposition ( string $preposition ): void {
		if ( ! $preposition ) {
			throw new IllegalArgumentException( "Preposition must be provided" );
		} else if ( $preposition != IEvent::PREPOSITION_BEFORE && $preposition != IEvent::PREPOSITION_ON ) {
			throw new InvalidArgumentException( "Unknown preposition '{$preposition}' provided" );
		}

		$this->preposition = $preposition;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent::getPreposition()
	 */
	public function getPreposition ( ): string {
		return $this->preposition;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent::stopPropogation()
	 */
	public function stopPropogation ( ) {
		$this->is_propogating = false;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Event\IEvent::isPropogating()
	 */
	public function isPropogating ( ) {
		return $this->is_propogating;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Event\IEvent::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Event\IEvent::fire()
	 */
	public function fire ( ) {
		EventDispatcher::getInstance()->dispatch( $this );
	}
}

