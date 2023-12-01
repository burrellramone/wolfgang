<?php

namespace Wolfgang\Message\SMTP;

use Wolfgang\Interfaces\Message\SMTP\IMessage as IMail;
use Wolfgang\Message\Message as WolfgangMessage;
use Wolfgang\Traits\Message\SMTP\TMessage as TMail;
use Wolfgang\Util\Token;

/**
 *
 * @package Wolfgang\Message\SMTP
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
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
