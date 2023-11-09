<?php

namespace Wolfgang\Interfaces\Event;

use Wolfgang\Interfaces\Model\IModel;

interface IEvent {
	const PREPOSITION_BEFORE = 'before';
	const PREPOSITION_ON = 'on';

	public function fire ( );

	/**
	 * Gets an instance of the subject of this event
	 *
	 * @return IModel The subject of this event
	 */
	public function getSubject ( ): IModel;

	/**
	 * Gets an instance of the object of this event
	 *
	 * @return IModel
	 */
	public function getObject ( ): IModel;

	/**
	 *
	 * @return string
	 */
	public function getAction ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getName ( ): string;
	/**
	 *
	 * @return string
	 */
	public function getPreposition ( ): string;

	/**
	 * Stops the propgation of this event
	 *
	 * @return NULL
	 */
	public function stopPropogation ( );

	/**
	 * Determines whether or not this event is being propogated
	 *
	 * @return bool true if the event is being propogated, false otherwise
	 */
	public function isPropogating ( );
}