<?php

namespace Wolfgang\Test;

// PHPUnit
use PHPUnit\Framework\TestCase;

// Wolfgang
use Wolfgang\Application\Context;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
abstract class Test extends TestCase {
	
	protected function setUp ( ):void {
		parent::setUp();
		
		if ( gethostname() == HOST_BOCCHERINI ) {
			$GLOBALS[ 'SUPER_APPLICATION_ENVIRONMENT' ] = Context::ENVIRONMENT_UNIT;
		}
	}
	
	protected function tearDown ( ):void {
		parent::tearDown();
	}
}