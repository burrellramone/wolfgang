<?php

namespace Wolfgang\Exceptions;

use Wolfgang\Interfaces\IMarshallable;

/**
 *
 * @package Wolfgang\Exceptions
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
class Exception extends \Exception implements IMarshallable {

	/**
	 *
	 * @param string $message
	 * @param number $code
	 * @param \Exception $previous
	 */
	public function __construct ( $message = "", $code = 0, $previous = null) {
		if ( $previous ) {
			if ( $message ) {
				$message = "{$message} due to {$previous->getMessage()}";
			} else {
				$message = $previous->getMessage();
			}
		}

		parent::__construct( $message, $code, $previous );
	}

	public function getTraceAsArray ( ) {
		$string_trace = $this->getTraceAsString();
		return array_filter( preg_split( "/(#[\d]{1,})/", $string_trace ) );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\IMarshallable::marshall()
	 */
	public function marshall ( ): array {
		$exception = [ 
				'message' => $this->getMessage(),
				'code' => $this->getCode(),
				'trace' => $this->getTraceAsString(),
				'file' => $this->getFile(),
				'line' => $this->getLine()
		];

		if ( $this->getPrevious() ) {
			$exception[ 'trace' ] = $this->getPrevious()->getTraceAsString();
		}

		return $exception;
	}
}