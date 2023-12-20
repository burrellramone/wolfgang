<?php

namespace Wolfgang\Message\SMTP;

use Wolfgang\Interfaces\Message\SMTP\IMessage as IMail;
use Wolfgang\Message\Message as WolfgangMessage;
use Wolfgang\Traits\Message\SMTP\TMessage as TMail;
use Wolfgang\Util\Token;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class Message extends WolfgangMessage implements IMail {
	use TMail;
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\SMTP\IMessage::getMessageId()
	 */
	public function getMessageId ( ): string {
		$message_id = $this->getHeader( 'Message-ID' );
		
		if ( ! $message_id ) {
			$message_id = Token::generate() . '@wolfgang.com';
		}
		
		$this->setHeader( 'Message-ID', $message_id );
		
		return $message_id;
	}
}
