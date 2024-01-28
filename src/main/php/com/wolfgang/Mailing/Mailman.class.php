<?php

namespace Wolfgang\Mailing;

use ArrayObject;
use PHPMailer\PHPMailer\PHPMailer;
// ////
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Exceptions\SMTP\Exception as MailException;
use Wolfgang\Interfaces\Message\SMTP\IMessage as IMail;
use Wolfgang\Application\Context;
use Wolfgang\Config\Mailing;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Date\DateTime;
use Wolfgang\Interfaces\Model\IModel;
use Wolfgang\Interfaces\IEmailContact;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Mailman extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var string
	 */
	private $callback_skin_domain_api_url;
	/**
	 *
	 * @var PHPMailer
	 */
	private $mailer;
	
	/**
	 *
	 * @var ArrayObject
	 */
	private $emails;
	
	/**
	 *
	 * @var array
	 */
	private static $headers = array (
			"MIME-Version" => "1.0",
			"Content-type" => "text/html; charset=utf-8",
			"X-Mailer: PHP/" => _PHP_VERSION_,
			"X-WOLFGANG-VERSION: " => WOLFGANG_VERSION
	);
	private $mailing_config = [ ];
	
	/**
	 */
	protected function init ( ) {
		parent::init();
		
		$this->mailing_config = Mailing::getAll();
		$this->mailer = new PHPMailer( true );
		$this->mailer->isSMTP();
		
		if ( ! Context::getInstance()->isProduction() ) {
			$this->mailer->SMTPDebug = 3;
			//$this->mailer->SMTPSecure = false;
			//$this->mailer->SMTPAutoTLS = false;
		}
		
		$this->mailer->AuthType = 'LOGIN';
		$this->mailer->SMTPSecure = 'tls';
		$this->mailer->isHTML( true );
		$this->mailer->SMTPAuth = true;
		$this->mailer->Port = $this->mailing_config[ 'smtps' ][ 'port' ];
		$this->mailer->Host = $this->mailing_config[ 'smtps' ][ 'host' ];
		
		$this->mailer->SMTPOptions = array (
				'ssl' => array (
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
		
		$this->emails = new \ArrayObject( [ ] );
	}
	
	/**
	 *
	 * @throws Exception
	 * @throws MailException
	 * @return int
	 */
	public function deliver ( ): int {
		$email_sent = 0;
		
		foreach ( $this->emails as &$email ) {
			try {
				$from_username = $email->getHeader( 'From' );
				
				if ( ! $from_username ) {
					$from_username = $this->getDefaultMailingUser();
				}
				
				if ( ! $this->mailingUserExists( $from_username ) ) {
					throw new Exception( "Unconfigured mailing user '{$from_username}'" );
				}
				
				$this->mailer->Username = $from_username;
				$this->mailer->Password = $this->getMailingUserPassword( $from_username );
				
				$this->mailer->setFrom( $from_username );
				$this->mailer->Subject = $email->getSubject();
				$this->mailer->Body = $email->getBody();
				$this->mailer->AltBody = strip_tags( $email->getBody() );
				
				$this->addCcs( $email->getCCs() );
				$this->addBccs( $email->getBcCs() );
				$this->addReplyTos( $email->getReplyTos() );
				$this->addRecipients( $email->getRecipients() );
				// $this->addAttachments( $email->getAttachments() );
				$this->mailer->addCustomHeader( "Message-ID", $email->getMessageId() );
				
				$this->mailer->send();
				
				$email_sent ++;
			} catch ( \Exception $e ) {
				throw new MailException( "Email with id '{$email->getMessageId()}' could not be sent. Error: {$e->getMessage()}." );
			}
			
			if ( ($email instanceof IModel) ) {
				$email->setDateTimeSent( new DateTime() );
				$email->save();
			}
		}
		
		return $email_sent;
	}
	
	/**
	 *
	 * @param string $user
	 * @return bool
	 */
	private function mailingUserExists ( string $user ): bool {
		return array_key_exists( $user, $this->mailing_config[ 'users' ] );
	}
	
	/**
	 *
	 * @return string
	 */
	private function getDefaultMailingUser ( ): string {
		$mailing_users = array_keys( $this->mailing_config[ 'users' ] );
		return $mailing_users[ 0 ];
	}
	
	/**
	 *
	 * @param string $user
	 * @throws Exception
	 * @return string|NULL
	 */
	private function getMailingUserPassword ( string $user ): ?string {
		if ( ! $user ) {
			throw new Exception( "Mailing user not provided" );
		} else if ( ! $this->mailingUserExists( $user ) ) {
			return null;
		}
		
		return $this->mailing_config[ 'users' ][ $user ];
	}
	
	/**
	 *
	 * @param \ArrayObject $email_list
	 */
	public function addEmails ( ArrayObject $emails ) {
		foreach ( $this->emails as $email ) {
			$this->addEmail( $email );
		}
	}
	
	/**
	 *
	 * @param IMail $email
	 */
	public function addEmail ( IMail &$email ): void {
		foreach ( self::$headers as $header_name => $value ) {
			$email->setHeader( $header_name, $value );
		}
		
		$this->emails->append( $email );
	}
	
	/**
	 *
	 * @param ArrayObject $attachments
	 */
	private function addAttachments ( array $attachments ): void {
		foreach ( $attachments as $attachment ) {
			$this->mailer->addAttachment( $attachment[ 'path' ], $attachment[ 'name' ], $attachment[ 'encoding' ], $attachment[ 'mime_type' ], $attachment[ 'disposition' ] );
		}
	}
	
	/**
	 *
	 * @param array $recipients
	 */
	private function addRecipients ( array $recipients ): void {
		foreach ( $recipients as $recipient ) {
			$this->addRecipient( $recipient );
		}
	}
	
	/**
	 *
	 * @param string $address
	 * @param string $name
	 */
	private function addRecipient ( IEmailContact $email_contact ): void {
		$this->mailer->addAddress( $email_contact->getEmail(), $email_contact->getName() );
	}
	
	/**
	 *
	 * @param array $reply_tos
	 */
	private function addReplyTos ( array $reply_tos ): void {
		foreach ( $reply_tos as $reply_to ) {
			$this->addReplyTo( $reply_to );
		}
	}
	
	/**
	 *
	 * @param IEmailContact $email_contact
	 */
	private function addReplyTo ( IEmailContact $email_contact ): void {
		$this->mailer->addReplyTo( $email_contact->getEmail(), $email_contact->getName() );
	}
	
	/**
	 *
	 * @param array $ccs
	 */
	private function addCcs ( array $ccs ): void {
		foreach ( $ccs as $cc ) {
			$this->addCc( $cc );
		}
	}
	
	/**
	 *
	 * @param IEmailContact $email_contact
	 */
	private function addCc ( IEmailContact $email_contact ): void {
		$this->mailer->addCC( $email_contact->getEmail(), $email_contact->getName() );
	}
	
	/**
	 *
	 * @param array $bccs
	 */
	private function addBccs ( array $bccs ): void {
		foreach ( $bccs as $bcc ) {
			$this->addBcc( $bcc );
		}
	}
	
	/**
	 *
	 * @param IEmailContact $email_contact
	 */
	private function addBcc ( IEmailContact $email_contact ): void {
		$this->mailer->addBCC( $email_contact->getEmail(), $email_contact->getName() );
	}
}
