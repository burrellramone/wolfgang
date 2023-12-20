<?php

namespace Wolfgang\Traits\Message\SMTP;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Traits\Message\TMessage as TWolfgangMessage;
use Wolfgang\Interfaces\IEmailContact;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TMessage {
	use TWolfgangMessage;
	
	/**
	 *
	 * @var string
	 */
	protected $subject;
	
	/**
	 *
	 * @var array
	 */
	protected $recipients = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected $reply_tos = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected $ccs = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected $bccs = [ ];
	
	/**
	 *
	 * @param string $subject
	 * @throws InvalidArgumentException
	 */
	public function setSubject ( string $subject ) {
		if ( ! $subject ) {
			throw new InvalidArgumentException( 'Subject not provided' );
		}
		
		$this->subject = $subject;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSubject ( ): string {
		return $this->subject;
	}
	
	/**
	 *
	 * @param IEmailContact $email_contact
	 */
	public function addRecipient ( IEmailContact $email_contact ) {
		$this->recipients[] = $email_contact;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getRecipients ( ): array {
		return $this->recipients;
	}
	
	/**
	 * '
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\SMTP\IMessage::getCcs()
	 */
	public function getCcs ( ): array {
		return $this->ccs;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\SMTP\IMessage::getBccs()
	 */
	public function getBccs ( ): array {
		return $this->bccs;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\SMTP\IMessage::getReplyTos()
	 */
	public function getReplyTos ( ): array {
		return $this->reply_tos;
	}
}