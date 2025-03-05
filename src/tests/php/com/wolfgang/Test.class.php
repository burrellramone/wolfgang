<?php

namespace Wolfgang\Test;

// PHPUnit
use PHPUnit\Framework\TestCase;

// Wolfgang
use Wolfgang\Application\Context;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Test extends TestCase {
	
	protected function setUp ( ):void {
		parent::setUp();
	}
	
	protected function tearDown ( ):void {
		parent::tearDown();
	}
}