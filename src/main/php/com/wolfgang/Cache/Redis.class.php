<?php

namespace Wolfgang\Cache;

use Wolfgang\Exceptions\MethodNotImplementedException;
use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Cache
 * @since Version 1.0.0
 */
final class Redis extends Cacher {

	/**
	 *
	 * @access public
	 */
	public function __construct ( ) {
		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Cache\Cacher::init()
	 */
	protected function init ( ) {
		parent::init();
	}

	/**
	 *
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( ! self::$instance ) {
			self::$instance = new Redis();
		}
		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::set()
	 */
	public function set ( $key, $value, $expiration = Memcached::EXPIRATION_TIME): bool {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::replace()
	 */
	public function replace ( $key, $value, $expiration = 0) {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::get()
	 */
	public function get ( $key ) {
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::delete()
	 */
	public function delete ( $key, $time = 0): bool {
	}

	public function fetchAll ( ) {
		throw new MethodNotImplementedException( "" );
	}

	public function flush ( ) {
		throw new MethodNotImplementedException( "" );
	}

	public function quit ( ) {
		throw new MethodNotImplementedException( "" );
	}

	public function __destruct ( ) {
	}
}
