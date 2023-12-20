<?php

namespace Wolfgang\Interfaces\Message\SMTP;

use Wolfgang\Interfaces\Message\IMessage as IWolfgangMessage;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
interface IMessage extends IWolfgangMessage {
	
	/**
	 * Gets the Message-ID header value for this email
	 *
	 * @return string
	 */
	public function getMessageId ( ): string;
	
	/**
	 *
	 * @return array
	 */
	public function getCcs ( ): array;
	
	/**
	 *
	 * @return array
	 */
	public function getBccs ( ): array;
	
	/**
	 *
	 * @return array
	 */
	public function getReplyTos ( ): array;
}
