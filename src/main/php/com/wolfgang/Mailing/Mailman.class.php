<?php

namespace Wolfgang\Mailing;

use ArrayObject;
use PHPMailer\PHPMailer\PHPMailer;
use Exception;

//Wolfgang
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Exceptions\SMTP\Exception as MailException;
use Wolfgang\Interfaces\Message\SMTP\IMessage as IMail;
use Wolfgang\Application\Context;
use Wolfgang\Config\Mailing;
use Wolfgang\Interfaces\IEmailContact;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Util\EmailContact;
use Wolfgang\Util\Logger\Logger;
use Wolfgang\Exceptions\InvalidArgumentException;

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
		
		$context = Context::getInstance();

		if ( ! $context->isProduction() && $context->isCli()) {
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
	 * @param callable|null $email_callback
	 * @return int
	 */
	public function deliver ( callable $email_callback = null): int {
		$total_emails_sent = 0;

		foreach ( $this->emails as &$email ) {
			try {
				$this->mailer->clearAllRecipients();
				$this->mailer->ClearAddresses();
				$this->mailer->ClearCCs();
				$this->mailer->ClearBCCs();

				$from_email = $email->getHeader( 'From' );
				
				if ( ! $from_email ) {
					throw new InvalidStateException("Email does not have 'From' header.");
				}
				
				if ( ! $this->mailingUserExists( $from_email, $user_config_values ) ) {
					throw new Exception( "Unconfigured mailing email '{$from_email}'" );
				}
				
				$from_email_contact = EmailContact::fromString($from_email);

				$this->mailer->Username = $from_email_contact->getEmailUser();
				$this->mailer->Password = $user_config_values['password'];
				
				$this->mailer->setFrom( $from_email_contact->getEmail(), $from_email_contact->getName() );
				$this->mailer->Subject = $email->getSubject();
				$this->mailer->Body = $email->getBody();
				$this->mailer->AltBody = strip_tags( $email->getBody() );
				
				$this->addCcs( $email->getCCs() );
				$this->addBccs( $email->getBcCs() );
				$this->addReplyTos( $email->getReplyTos() );
				$this->addRecipients( $email->getRecipients() );
				$this->addCustomHeader( "Message-ID", $email->getMessageId() );
				
				$sent = $this->mailer->send();

				if($email_callback){
					$email_callback($email, $sent);
				}
				
				if($sent){
					$total_emails_sent ++;
				}
				
			} catch ( Exception $e ) {

				if($email_callback){
					$email_callback($email, false);
				}
				
				throw new MailException( "Email with id '{$email->getMessageId()}' could not be sent. Error: {$e->getMessage()}.", 0, $e );
			}
		}
		
		return $total_emails_sent;
	}
	
	/**
	 *
	 * @param string $user
	 * @param array $user_config_values
	 * @return bool
	 */
	private function mailingUserExists ( string $user, &$user_config_values = null): bool {
		foreach( $this->mailing_config as $key => $values) {
			if($key == 'smtp' || $key == 'smtps'){
				continue;
			}

			try {
				$ec1 = EmailContact::fromString($user);
				
				if($ec1) {
					$ec2 = new EmailContact($values['email'], $values['name']);

					if($ec1->equals($ec2)){
						$user_config_values = $values;
						return true;
					}
				}
			} catch (Exception $e) {
				Logger::getLogger()->info($e);
			}
		}

		return false;
	}
	
	/**
	 *
	 * @param \ArrayObject $email_list
	 */
	public function addEmails ( ArrayObject $emails ):void {
		foreach ( $emails as $email ) {
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
	 * @return void
	 */
	private function addRecipients ( array $recipients ): void {
		foreach ( $recipients as $recipient ) {
			$this->addRecipient( $recipient );
		}
	}
	
	/**
	 *
	 * @param IEmailContact|string $email_contact
	 * @return void
	 */
	private function addRecipient ( IEmailContact|string $email_contact ): void {
		if(is_string($email_contact)){
			if(empty($email_contact)){
				throw new InvalidArgumentException("Email contact not provided");
			}

			$ec = EmailContact::fromString($email_contact);

			if(!$email_contact){
				throw new InvalidArgumentException("Email address '{$email_contact}' is not a valid");
			}

			$email_contact = $ec;
		}

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
	 * @param IEmailContact|string $email_contact
	 * @return void
	 */
	private function addReplyTo ( IEmailContact|string $email_contact ): void {
		if(is_string($email_contact)){
			if(empty($email_contact)){
				throw new InvalidArgumentException("Email contact not provided");
			}

			$ec = EmailContact::fromString($email_contact);

			if(!$email_contact){
				throw new InvalidArgumentException("Email address '{$email_contact}' is not a valid");
			}

			$email_contact = $ec;
		}
		
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

	/**
	 * @param string $name
	 * @param string $value
	 */
	private function addCustomHeader (string $name, string $value) {
		$this->mailer->addCustomHeader( $name, $value );
	}
}