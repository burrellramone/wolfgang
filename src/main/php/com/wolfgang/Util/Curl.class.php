<?php

namespace Wolfgang\Util;

use Exception;

//
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Config\Curl as CurlConfig;
use Wolfgang\Encoding\JSON;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Curl extends Component implements ISingleton {
	/**
	 *
	 * @var Curl
	 */
	protected static $instance;
	
	/**
	 *
	 * @var resource
	 */
	private $curl_handle;
	
	/**
	 *
	 * @var resource
	 */
	private $error_file;
	
	/**
	 *
	 * @var mixed
	 */
	private $info;
	
	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}
	
	protected function init ( ) {
		parent::init();
	}
	
	/**
	 *
	 * @return Curl
	 */
	public static function getInstance ( ): Curl {
		if ( empty( self::$instance ) ) {
			self::$instance = new Curl();
		}
		return self::$instance;
	}
	
	/**
	 *
	 * @param string $url
	 * @param array $options
	 * @param bool $json_decode
	 * @return mixed
	 */
	private function sendRequest ( $url, array $options = array(), $json_decode = FALSE) {
		$cookiejar_filepath = CURL_DIRECTORY . "/cookies.dat";
		$errorfile_filepath = CURL_DIRECTORY . "/cerror.dat";

		$this->curl_handle = curl_init();
		
		curl_setopt( $this->curl_handle, CURLOPT_URL, $url );
		
		$this->error_file = fopen( $errorfile_filepath , 'w' );
		
		if ( empty( $options ) ) {
			$options = array ();
		}
		
		if ( empty( $options[ "headers" ] ) ) {
			$options[ "headers" ] = array ();
		}
		
		if ( empty( $options[ "params" ] ) ) {
			$options[ "params" ] = array ();
		}
		
		$options[ "headers" ] = array_merge( $options[ "headers" ], array (
				"User-Agent: " . CurlConfig::get( 'user_agent' ),
				"Content-length: " . strlen( http_build_query( $options[ 'params' ] ) )
		) );
		
		curl_setopt( $this->curl_handle, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $this->curl_handle, CURLOPT_COOKIEJAR, $cookiejar_filepath );
		curl_setopt( $this->curl_handle, CURLOPT_COOKIEFILE, $cookiejar_filepath );
		curl_setopt( $this->curl_handle, CURLOPT_STDERR, $this->error_file );
		curl_setopt( $this->curl_handle, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt( $this->curl_handle, CURLOPT_TIMEOUT, 60 );
		
		if ( ! empty( $options[ "method" ] ) ) {
			if ( $options[ "method" ] == "POST" ) {
				if ( ! empty( $options[ "params" ] ) ) {
					curl_setopt( $this->curl_handle, CURLOPT_POSTFIELDS, is_array( $options[ "params" ] ) ? http_build_query( $options[ "params" ] ) : $options[ "params" ] );
				}
				curl_setopt( $this->curl_handle, CURLOPT_POST, true );
			}
		}
		
		if ( ! empty( $options[ "headers" ] ) ) {
			curl_setopt( $this->curl_handle, CURLOPT_HTTPHEADER, $options[ "headers" ] );
		}
		
		if ( ! empty( $options[ "follow_redirect" ] ) ) {
			curl_setopt( $this->curl_handle, CURLOPT_FOLLOWLOCATION, 1 );
		}
		
		if ( ! empty( $options[ 'verbose' ] ) ) {
			curl_setopt( $this->curl_handle, CURLOPT_VERBOSE, true );
		}
		
		if ( ! empty( $options[ 'header' ] ) ) {
			curl_setopt( $this->curl_handle, CURLOPT_HEADER, true );
		}
		
		if ( ! empty( $options[ 'username' ] ) && ! empty( $options[ 'password' ] ) ) {
			curl_setopt( $this->curl_handle, CURLOPT_USERPWD, "{$options['username']}:{$options['password']}" );
			curl_setopt( $this->curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		}
		
		$output = curl_exec( $this->curl_handle );
		$this->info = curl_getinfo( $this->curl_handle );
		
		curl_close( $this->curl_handle );
		
		if ( $json_decode && $output ) {
			try {
				$output = JSON::decode( $output, true );
			} catch(Exception $e) {
				throw new Exception("Could not JSON decode response '{$output}'", 0 , $e);
			}
		}
		
		return $output;
	}

	/**
	 * Gets the info of the last CURL request
	 * 
	 * @see https://www.php.net/manual/function.curl-getinfo.php
	 * @return array The info of the last curl request
	 */
	public function getInfo(): array {
		return $this->info;
	}
	
	/**
	 * Performs a POST HTTP request
	 *
	 * @param string $url
	 * @param array $options
	 * @param bool $json_decode
	 * @return mixed
	 */
	public function post ( $url, array $options = array(), $json_decode = false) {
		return $this->sendRequest( $url, $options + array (
				"method" => "POST"
		), $json_decode );
	}
	
	/**
	 * Performs a GET HTTP request
	 *
	 * @param string $url
	 * @param array $options
	 * @param bool $json_decode
	 * @return mixed
	 */
	public function get ( $url, array $options = array(), $json_decode = false) {
		return $this->sendRequest( $url, $options + array (
				"method" => "GET"
		), $json_decode );
	}
	
	/**
	 * Performs a PUT HTTP request
	 *
	 * @param string $url
	 * @param array $options
	 * @param bool $json_decode
	 * @return mixed
	 */
	public function put ( $url, array $options = array(), $json_decode = false) {
		return $this->sendRequest( $url, $options + array (
				"method" => "PUT"
		) );
	}
	
	/**
	 * Performs a DELETE HTTP request
	 *
	 * @param string $url
	 * @param array $options
	 * @param bool $json_decode
	 * @return mixed
	 */
	public function delete ( $url, array $options = array(), $json_decode = false) {
		return $this->sendRequest( $url, $options + array (
				"method" => "DELETE"
		) );
	}
	
	/**
	 */
	public function getResponseCode ( ) {
		return $this->info[ 'response_code' ];
	}
}
