<?php

namespace Wolfgang\Cache;

use Wolfgang\Interfaces\Cache\ICacher;
use Wolfgang\Config\Cache as CacheConfig;
use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Cacher extends Component implements ICacher , ISingleton {

	/**
	 *
	 * @var \Wolfgang\Interfaces\Cache\ICacher
	 */
	protected static $instance;

	/**
	 *
	 * @var array
	 */
	protected $cache_config = [ ];

	/**
	 *
	 * @var bool
	 */
	protected $enabled;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->cache_config = CacheConfig::getAll();
	}

	/**
	 *
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( empty( self::$instance ) ) {
			switch ( CacheConfig::get( 'type' ) ) {
				case ICacher::TYPE_MEMCACHED :
					self::$instance = Memcached::getInstance();
					break;

				default :
					self::$instance = Memcached::getInstance();
					break;
			}
		}
		return self::$instance;
	}
}