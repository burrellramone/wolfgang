<?php

namespace Wolfgang\Cache;

use Memcached as PHPMemcached;
use Wolfgang\Config\Cache as CacheConfig;
use Wolfgang\Exceptions\Cache\Exception as CachingException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Traits\TSingleton;

/**
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses \Memcached
 * @uses Wolfgang\Interfaces\ISingleton
 * @uses Wolfgang\Interfaces\ICacher
 * @uses Wolfgang\Session\Manager
 * @uses Wolfgang\Session\Session
 * @since Version 0.1.0
 */
final class Memcached extends Cacher {
	use TSingleton;

	/**
	 *
	 * @var int
	 */
	const EXPIRATION_TIME = HOUR_IN_SECONDS * 2;

	/**
	 *
	 * @var PHPMemcached
	 */
	protected $memcached;

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Cache\Cacher::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->enabled = ( bool ) $this->cache_config[ 'enabled' ];

		if ( $this->enabled ) {

			$this->memcached = new PHPMemcached( /*Application::getInstance()->getKind()*/ );
			$this->memcached->setOption( PHPMemcached::DISTRIBUTION_CONSISTENT, true );
			$this->memcached->setOption( PHPMemcached::OPT_SERIALIZER, PHPMemcached::SERIALIZER_IGBINARY );

			$servers = $this->cache_config[ 'memcached' ];

			foreach ( $servers as $server ) {
				$this->memcached->addServer( $server[ 'host' ], $server[ 'port' ], $server[ 'weight' ] );
			}
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::set()
	 */
	public function set ( $key, $value, $expiration = Memcached::EXPIRATION_TIME): bool {
		if ( ! $key ) {
			throw new IllegalArgumentException( "Key must be provided" );
		} else if ( ! $this->enabled ) {
			return true;
		}

		$result = $this->memcached->set( $key, $value, $expiration );

		if ( ! $result ) {
			if ( ! in_array( $this->memcached->getResultCode(), array (
					PHPMemcached::RES_STORED,
					PHPMemcached::RES_SUCCESS
			) ) ) {
				throw new CachingException( "Unable to cache item with key {$key}. Result Code: {$this->memcached->getResultCode()}. Result Message: {$this->memcached->getResultMessage()}" );
			}
		}

		return $result;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::replace()
	 */
	public function replace ( $key, $value, $expiration = 0) {
		if ( ! $this->enabled ) {
			return null;
		} else if ( empty( $key ) ) {
			throw new IllegalArgumentException( "Key must be provided" );
		}

		return $this->memcached->replace( $key, $value, $expiration );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::get()
	 */
	public function get ( $key ) {
		if ( ! $key ) {
			throw new IllegalArgumentException( "Key must be provided" );
		} else if ( ! CacheConfig::get( 'enabled' ) ) {
			return null;
		}

		return $this->memcached->get( $key );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::delete()
	 */
	public function delete ( $key, $time = 0): bool {
		if ( ! $key ) {
			throw new IllegalArgumentException( "Key must be provided" );
		} else if ( ! $this->enabled ) {
			return true;
		}

		$result = $this->memcached->delete( $key, $time );

		if ( ! $result ) {
			if ( ! in_array( $this->memcached->getResultCode(), array (
					PHPMemcached::RES_DELETED,
					PHPMemcached::RES_NOTFOUND
			) ) ) {
				throw new CachingException( "Unable to uncache item with key {$key}. Result Code: {$this->memcached->getResultCode()}. Result Message: {$this->memcached->getResultMessage()}" );
			}
		}

		return $result;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::fetchAll()
	 */
	public function fetchAll ( ): array {
		if ( ! $this->enabled ) {
			return [ ];
		}

		return $this->memcached->fetchAll();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::flush()
	 */
	public function flush ( ) {
		if ( ! $this->enabled ) {
			return;
		}

		$this->memcached->flush();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Cache\ICacher::quit()
	 */
	public function quit ( ) {
		if ( ! $this->enabled ) {
			return;
		}

		$this->memcached->quit();
	}
}
