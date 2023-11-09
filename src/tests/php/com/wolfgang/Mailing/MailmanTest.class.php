<?php

namespace Wolfgang\Test\Mailing;

use Wolfgang\Test\Test;
use Wolfgang\Mailing\Mailman;
use Wolfgang\Message\SMTP\Message as Mail;
use Wolfgang\Util\EmailContact;
use Wolfgang\Config\Mailing;
use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 */
final class MailmanTest extends Test {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Test\Test::setUp()
	 */
	protected function setUp ( ) {
		parent::setUp();
	}
	
	/**
	 * Test that the Mailman class can send emails
	 */
	public function testCanSendMail ( ) {
		$mailman = Mailman::getInstance();
		$email = new Mail();
		
		$mailing_configuration = Mailing::getAll();
		
		// Get unittester mailing user from current environment mailing configuration
		$unittester_email = '';
		
		foreach ( $mailing_configuration[ 'users' ] as $mailing_user => $password ) {
			if ( preg_match( "/unittester/", $mailing_user ) ) {
				$unittester_email = $mailing_user;
				break;
			}
		}
		
		if ( ! $unittester_email ) {
			throw new Exception( "Unable to determine unittester email address" );
		}
		
		$email->setHeader( "From", $unittester_email );
		$email->setSubject( "Unit Test Email " . date( "Y-m-d H:i:s" ) );
		$email->setBody( "Unit Test" );
		$email->addRecipient( new EmailContact( $unittester_email, 'Unit Tester' ) );
		
		$mailman->addEmail( $email );
		$this->assertEquals( 1, $mailman->deliver(), "Mail not delivered" );
	}
}