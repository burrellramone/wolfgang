<?php

namespace Extension\Model;

use Extension\Extension as ExtensionComponent;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 */
abstract class Extension extends ExtensionComponent {

	/**
	 *
	 * @return string|null
	 */
	public function getId ( ): ?string {
		return $this->getSubject()->getId();
	}
}
