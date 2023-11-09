<?php

namespace Wolfgang\Interfaces\Message;

/**
 *
 * @package Wolfgang\Interfaces\Message
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IRequest extends IMessage {

	/**
	 * Gets the value of a parameter that was sent in this request
	 *
	 * @param string $paramater
	 */
	public function getParameter ( string $paramater );

	/**
	 *
	 * @return array
	 */
	public function getParameters ( ): array;
}