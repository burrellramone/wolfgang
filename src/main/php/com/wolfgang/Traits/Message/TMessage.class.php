<?php

namespace Wolfgang\Traits\Message;

use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
trait TMessage {

	/**
	 * The body of the message. String if it has been set, null otherwise
	 *
	 * @var string|null
	 */
	protected $body = '';

	/**
	 *
	 * @var array
	 */
	protected $headers = [ ];

	/**
	 *
	 * @param string $body
	 */
	public function setBody ( string $body ) {
		$this->body = $body;
	}

	/**
	 *
	 * @return string
	 */
	public function getBody ( ): ?string {
		return $this->body;
	}

	/**
	 * Gets a particular header value by a provided header name
	 *
	 * @return string|NULL
	 */
	public function getHeader ( string $header ): ?string {
		if ( ! $header ) {
			throw new InvalidArgumentException( "Header not provided" );
		}

		if ( ! array_key_exists( $header, $this->headers ) ) {
			return null;
		}

		return $this->headers[ $header ];
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setHeader ( string $name, string $value ) {
		$this->headers[ $name ] = $value;
	}
}