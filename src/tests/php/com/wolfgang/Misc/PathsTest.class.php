<?php

namespace Wolfgang\Test\Mailing;

use Wolfgang\Test\Test;
use Wolfgang\Mailing\Mailman;
use Wolfgang\Message\SMTP\Message as Mail;
use Wolfgang\Util\EmailContact;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 */
final class PathsTest extends Test {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Test\Test::setUp()
	 */
	protected function setUp ( ):void {
		parent::setUp();
	}
	
	public function testConfigLocationExists ( ) {
		$config_directory = WOLFGANG_DIRECTORY . "config/";
		
		$this->assertDirectoryExists( $config_directory, "Configuration directory '{$config_directory}' does not exist" );
	}
}