<?php

namespace Wolfgang\Exceptions\Message\HTTP;

use Exception as PHPException;

//Wolfgang
use Wolfgang\Exceptions\Exception as ComponentException;
use Wolfgang\Interfaces\IHttpException;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Exception extends ComponentException implements IHttpException {
	/**
	 *
	 * @var string
	 */
	protected $protocol_version = '1.1';

	/**
	 *
	 * @var string
	 */
	protected $message = "HTTP Exception";

	/**
	 *
	 * @var string
	 */
	protected $http_code = 500;

	/**
	 *
	 * @var string
	 */
	protected $http_status = 'Internal Server Error';

	/**
	 *
	 * @var string
	 */
	protected $reason_phrase = '';

	/**
	 *
	 * @access protected
	 * @static
	 * @var array
	 */
	protected static $http_codes = array (
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
	 *
	 * @param string $message
	 * @param number $code
	 * @param PHPException|null $previous
	 */
	public function __construct ( string $message = "", int $code = 0, PHPException|null $previous = null) {
		parent::__construct( $message, $code, $previous );

		$this->reason_phrase = self::$http_codes[ $this->http_code ];
	}

	/**
	 *
	 * @return string
	 */
	public function getProtocolVersion ( ): string {
		return $this->protocol_version;
	}

	/**
	 * Get the corresponding HTTP code for this HTTP exception intance
	 *
	 * @return int
	 */
	public function getHttpCode ( ): int {
		return $this->http_code;
	}

	/**
	 * @return string
	 */
	public function getHTTPStatus ( ):string {
		return $this->http_status;
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
		return "HTTP/{$this->getProtocolVersion()} {$this->getHttpCode()} {$this->getReasonPhrase()}";
	}
}
