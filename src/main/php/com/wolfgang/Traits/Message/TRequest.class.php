<?php

namespace Wolfgang\Traits\Message;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Component\Traits\Message
 * @since Version 1.0.0
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
