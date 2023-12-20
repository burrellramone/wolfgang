<?php

namespace Wolfgang\Traits\Message;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TRequest {
	use TMessage;

	/**
	 *
	 * @var \stdClass
	 */
	protected $params;

	/**
	 *
	 * @param string $parameter
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParameter ( string $parameter, $default = null) {
		if ( isset( $this->params->{$parameter} ) ) {
			return $this->params->{$parameter};
		}

		return $default;
	}

	/**
	 *
	 * @return array
	 */
	public function getParameters ( ): array {
		return get_object_vars( $this->params );
	}
}
