<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Exception;
use Wolfgang\Interfaces\IEmailContact;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
class EmailContact extends Contact implements IEmailContact {
	
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
}
