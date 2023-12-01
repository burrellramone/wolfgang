<?php

namespace Wolfgang\Adapter;

use Wolfgang\Component as BaseComponent;

/**
 *
 * @package Wolfgang\Adapter
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
abstract class Component extends BaseComponent {

	protected function __construct ( ) {
		parent::__construct();
	}
}
