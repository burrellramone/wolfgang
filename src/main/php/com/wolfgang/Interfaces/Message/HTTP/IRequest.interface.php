<?php

namespace Wolfgang\Interfaces\Message\HTTP;

use Wolfgang\Interfaces\Message\IRequest as IComponentRequest;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
interface IRequest extends IMessage , IComponentRequest {

	/**
	 *
	 * @var string
	 */
	const METHOD_GET = 'GET';

	/**
	 *
	 * @var string
	 */
	const METHOD_HEAD = 'HEAD';

	/**
	 *
	 * @var string
	 */
	const METHOD_POST = 'POST';

	/**
	 *
	 * @var string
	 */
	const METHOD_PUT = 'PUT';

	/**
	 *
	 * @var string
	 */
	const METHOD_DELETE = 'DELETE';

	/**
	 *
	 * @var string
	 */
	const METHOD_CONNECT = 'CONNECT';

	/**
	 *
	 * @var string
	 */
	const METHOD_OPTIONS = 'OPTIONS';

	/**
	 *
	 * @var string
	 */
	const METHOD_TRACE = 'TRACE';

	/**
	 * Retrieves the HTTP method of the request.
	 *
	 * @return string.
	 */
	public function getMethod ( ): string;

	/**
	 * Retrieves the URI of the request.
	 *
	 * @return string.
	 */
	public function getUri ( );

	/**
	 * Gets the value of a parameter that was sent in this request
	 *
	 * @param string $paramater
	 */
	public function getParameter ( string $paramater );

	/**
	 *
	 * @return array
	 */
	public function getParameters ( ): array;

	/**
	 *
	 * @param string $header_name
	 */
	public function getHeader ( string $header_name );
}