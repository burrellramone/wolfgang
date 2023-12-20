<?php

namespace Wolfgang\Message\HTTP;

use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Message\HTTP\IResponse;
use Wolfgang\Exceptions\Message\HTTP\InvalidHttpStatusCodeException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Response extends Message implements ISingleton , IResponse {
	use TSingleton;
	/**
	 *
	 * @var string
	 */
	protected $protocol_version = '1.1';

	/**
	 *
	 * @var int
	 */
	protected $status_code = 200;

	/**
	 *
	 * @var string
	 */
	protected $reason_phrase = '';

	/**
	 *
	 * @var array
	 */
	protected $headers = [ ];

	/**
	 *
	 * @var array
	 */
	protected $data = array ();

	/**
	 *
	 * @access protected
	 * @var array
	 */
	protected $http_codes = array (
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => 'Switch Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			418 => 'I\'m a teapot',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			425 => 'Unordered Collection',
			426 => 'Upgrade Required',
			449 => 'Retry With',
			450 => 'Blocked by Windows Parental Controls',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			506 => 'Variant Also Negotiates',
			507 => 'Insufficient Storage',
			509 => 'Bandwidth Limit Exceeded',
			510 => 'Not Extended'
	);

	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * @param int $statusCode
	 */
	public function setStatusCode ( int $status_code ) {
		if ( empty( $status_code ) ) {
			throw new IllegalArgumentException( "An HTTP status code was not provided." );
		} else if ( ! in_array( $status_code, array_keys( $this->http_codes ) ) ) {
			throw new InvalidHttpStatusCodeException( "The HTTP status code '{$status_code}' is invalid" );
		}
		$this->status_code = $status_code;
		$this->reason_phrase = $this->http_codes[ $status_code ];
	}

	/**
	 *
	 * @return int
	 */
	public function getStatusCode ( ): int {
		return $this->status_code;
	}

	/**
	 *
	 * @param array|\stdClass $data
	 */
	public function setData ( $data ) {
		$this->data = $data;
	}

	/**
	 *
	 * @return array|\stdClass
	 */
	public function getData ( ) {
		return $this->data;
	}

	/**
	 *
	 * @return string
	 */
	public function getProtocolVersion ( ): string {
		return $this->protocol_version;
	}

	/**
	 *
	 * @return string
	 */
	public function getReasonPhrase ( ): string {
		return $this->reason_phrase;
	}

	/**
	 *
	 * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
	 */
	public function getStatusLine ( ): string {
		return "HTTP/{$this->getProtocolVersion()} {$this->getStatusCode()} {$this->getReasonPhrase()}";
	}

	/**
	 *
	 * @return boolean
	 */
	public function isError ( ): bool {
		return $this->getStatusCode() >= 400 ? true : false;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Message\HTTP\IResponse::setHeader()
	 */
	public function setHeader ( string $name, string $value ) {
		$this->headers[ $name ] = $value;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString ( ): string {
		foreach ( $this->headers as $key => $value ) {
			header( $key . ":" . $value );
		}

		return $this->getBody();
	}
}
