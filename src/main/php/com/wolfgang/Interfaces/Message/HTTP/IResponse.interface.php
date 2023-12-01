<?php

namespace Wolfgang\Interfaces\Message\HTTP;

use Wolfgang\Interfaces\Message\IResponse as IComponentResponse;

/**
 *
 * @package Wolfgang\Interfaces\HTTP
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @see http://airportruns.ca
 * @since Version 1.0.0
 */
interface IResponse extends IMessage , IComponentResponse {
	//@formatter:off
	
	/**
	 * 
	 * @var integer
	 */
	const STATUS_CODE_CONTINUE 							= 100;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_SWITCHING_PROTOCOLS 				= 101;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PROCESSING 						= 102;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_OK								= 200;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_CREATED 							= 201;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_ACCEPTED 							= 202;
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NON_AUTHORATATIVE_INFORMATION 	= 203;
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NO_CONTENT 						= 204;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_RESET_CONTENT 					= 205;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PARTIAL_CONTENT 					= 206;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_MULTI_STATUS 						= 207;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_MULTIPLE_CHOICES 					= 300;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_MOVED_PERMANENTLY 				= 301;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_FOUND 							= 302;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_SEE_OTHER 						= 303;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NOT_MODIFIED 						= 304;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_USE_PROXY 						= 305;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_SWITCH_PROXY 						= 306;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_TEMPORARY_REDIRECT 				= 307;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_BAD_REQUEST 						= 400;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_UNAUTHORIZED 						= 401;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PAYMENT_REQUIRED					= 402;
	
	/**
	 * 
	 * @var integer
	 */
	const STATUS_CODE_FORBIDDEN 						= 403;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NOT_FOUND							= 404;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_METHOD_NOT_ALLOWED 				= 405;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NOT_ACCEPTABLE 					= 406;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PROXY_AUTHENTICATION_REQUIRED 	= 407;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_REQUEST_TIMEOUT					= 408; 

	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_CONFLICT 							= 409;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_GONE 								= 410;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_LENGTH_REQUIRED 					= 411;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PRECONDITION_FAILED 				= 412;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PAYLOAD_TOO_LARGE 				= 413;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_URI_TOO_LONG 						= 414;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_UNSUPPORTED_MEDIA_TYPE 			= 415;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_RANGE_NOT_SATISFIABLE 			= 416;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_EXPECTATION_FAILED 				= 417;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_IM_A_TEA_POT 						= 418;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_MISDIRECTED_REQUEST 				= 421;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_UNPROCESSABLE_ENTITY 				= 422;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_LOCKED 							= 423;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_FAILED_DEPENDENCY					= 424;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_UPGRADE_REQUIRED					= 426;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_PRECONDITION_REQUIRED 			= 428; 
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_TOO_MANY_REQUESTS 				= 429; 
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_REQUEST_HEADER_FIELDS_TOO_LARGE	= 431; 
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_UNAVAILABLE_FOR_LEGAL_REASONS		= 451;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_INTERNAL_SERVER_ERROR 			= 500;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NOT_IMPLEMENTED 					= 501;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_BAD_GATEWAY 						= 502;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_SERVICE_UNAVAILABLE 				= 503;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_GATEWAY_TIMEOUT 					= 504;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_HTTP_VERSION_NOT_SUPPORTED 		= 505;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_VARIANT_ALSO_NEGOTIATES 			= 506;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_INSUFFICIENT_STORAGE 				= 507;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_LOOP_DETECTED 					= 508;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_BANDWIDTH_LIMIT_EXCEEDED 			= 509;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_CODE_NOT_EXTENDED 						= 510;
	
	/**
	 *
	 * @var integer
	 */
	const STATUS_NETWORD_AUTHENTICATION_REQUIRED 		= 511;
	// @formatter:on
	
	/**
	 * Gets the response status code. The status code is a 3-digit integer result code of the
	 * server's attempt to understand and satisfy the request.
	 *
	 * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 * @see http://www.restapitutorial.com/httpstatuscodes.html
	 * @return int
	 */
	public function getStatusCode ( ): int;
	
	/**
	 *
	 * @return string
	 */
	public function getProtocolVersion ( ): string;
	
	/**
	 *
	 * @return string
	 */
	public function getReasonPhrase ( ): string;
	
	/**
	 * Sets an HTTP header within this response.
	 *
	 * @param string $header_name The name of the header to set
	 * @param string $header_value The value of the header to set
	 */
	public function setHeader ( string $header_name, string $header_value );
}