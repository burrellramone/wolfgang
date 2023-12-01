<?php

namespace Wolfgang\Interfaces\Message;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 */
interface IMessage {
	
	/**
	 * Sets the body of the message.
	 */
	public function setBody ( string $body );
	
	/**
	 * Gets the body of the message. String if it has been set / initialized, null otherwise.
	 *
	 * @return string|NULL
	 */
	public function getBody ( ): ?string;
}
