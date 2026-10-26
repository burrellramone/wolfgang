<?php

namespace Controller;

use Wolfgang\Controller\Cli as CliController;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
abstract class Cli extends CliController {

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Controller\ApiController::init()
	 */
	protected function init ( ) {
		parent::init();

		//@TODO Log CLI call
	}
}
