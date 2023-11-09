<?php

namespace Wolfgang\Interfaces\Cache;

interface ICacher {
	
	/**
	 *
	 * @var string
	 */
	const TYPE_MEMCACHED = 'memcached';
	
	/**
	 *
	 * @var string
	 */
	const TYPE_REDIS = 'redis';
	
	public function set ( $key, $value, $expiration = 0 );
	
	public function get ( $key );
	
	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $expiration
	 */
	public function replace ( $key, $value, $expiration = 0 );
	
	public function delete ( $key, $time = 0 );
	
	public function fetchAll ( );
	
	public function flush ( );
	
	public function quit ( );
}
