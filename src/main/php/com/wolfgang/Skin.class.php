<?php

namespace Wolfgang;

use Wolfgang\Interfaces\ISkin;
use Wolfgang\Traits\TSkin;
use Wolfgang\Date\DateTime;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Skin extends Component implements ISkin {
	use TSkin;

	private $id;

	public function __construct(array $definition) {
		parent::__construct();

		$this->id = $definition['id'];
		$this->name = $definition['name'];

		$this->skin_domain = new SkinDomain($definition['skin_domain']);
	}

	public function getId():string|int {
		return $this->id;
	}
}
