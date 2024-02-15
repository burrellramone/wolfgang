<?php

namespace Wolfgang\Util;

use Stringable;

//Wolfgang
use Wolfgang\Exceptions\Exception;
use Wolfgang\Interfaces\IEmailContact;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class EmailContact extends Contact implements IEmailContact, Stringable {
	
	/**
	 *
	 * @var string
	 */
	protected $email;
	
	/**
	 *
	 * @param string $email
	 * @param string $name
	 */
	public function __construct ( string $email, string $name ) {
		parent::__construct( $name );
		
		$this->setEmail( $email );
	}
	
	/**
	 *
	 * @param string $email
	 * @throws Exception
	 */
	private function setEmail ( string $email ) {
		if ( ! Strings::validateEmail( $email ) ) {
			throw new Exception( "Email address '{$email}' is invalid" );
		}
		
		$this->email = $email;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IEmailContact::getEmail()
	 */
	public function getEmail ( ): string {
		return $this->email;
	}

	/**
	 * Creates an EmailContact instance from a provided string. The string MUST either being in the format
	 * local-part@domain or 'Name <local-part@domain>'
	 */
	public static function fromString( string $email_contact ):? EmailContact {
		if(filter_var($email_contact, FILTER_VALIDATE_EMAIL)) {
			return new EmailContact($email_contact, '');
		} else {
			$re = VALID_EMAIL_REGEX;
			$re = substr($re, 1);
			$re = substr($re, 0, -1);

			if (preg_match("/^([\w\s-]+) <($re)>/", $email_contact, $matches)) {
				return new EmailContact($matches[2], $matches[1]);
			}
		}

		return null;
	}

	/**
	 * @param EmailContact $ec
	 */
	public function equals(EmailContact $ec):bool {
		if($this->getEmail() === $ec->getEmail()) {
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getEmailUser():string {
		preg_match("/" . VALID_EMAIL_REGEX . "/", $this->getEmail(), $matches);
		return $matches[1];
	}

	public function __toString():string{
		$email_contact = '';

		if( !$this->name ){
			$email_contact = $this->email;
		} else {
			$email_contact = $this->name . " <{$this->email}>";
		}

		return $email_contact;
	}
}
