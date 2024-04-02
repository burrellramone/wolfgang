<?php

namespace Wolfgang\Traits\Message\SMTP;

use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Traits\Message\TMessage as TWolfgangMessage;
use Wolfgang\Interfaces\IEmailContact;
use Wolfgang\Util\EmailContact;

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
	protected array $recipients = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected array $reply_tos = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected array $ccs = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected array $bccs = [ ];
	
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
		$email_contact = new EmailContact($email_contact->getEmail(), $email_contact->getName());
		
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

	/**
	 *
	 * @param IEmailContact $reply_to
	 */
	public function addReplyTo ( IEmailContact $reply_to ) {
		$this->reply_tos[] = $reply_to;
	}
}