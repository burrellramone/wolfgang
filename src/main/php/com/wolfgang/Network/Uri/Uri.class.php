<?php

namespace Wolfgang\Network\Uri;

use Wolfgang\Interfaces\Network\IUri;
use Wolfgang\Component;
use Wolfgang\Exceptions\Network\Exception as NetworkException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
class Uri extends Component implements IUri {
	
	/**
	 *
	 * @var string
	 */
	protected $scheme = '';
	
	/**
	 *
	 * @var string
	 */
	protected $authority = '';
	
	/**
	 *
	 * @var string
	 */
	protected $user_info = '';
	
	/**
	 *
	 * @var string
	 */
	protected $host = '';
	
	/**
	 *
	 * @var int
	 */
	protected $port;
	
	/**
	 *
	 * @var string
	 */
	protected $path = '';
	
	/**
	 *
	 * @var string
	 */
	protected $query;
	
	/**
	 *
	 * @var string
	 */
	protected $fragment;
	
	/**
	 * The explicit representation of this URI
	 *
	 * @var string
	 */
	private $uri;
	
	public function __construct ( string $uri ) {
		$this->uri = $uri;
		
		parent::__construct();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$components = parse_url( $this->uri );
		
		if ( ! empty( $components[ 'scheme' ] ) ) {
			$this->setScheme( $components[ 'scheme' ] );
		}
		
		if ( ! empty( $components[ 'host' ] ) ) {
			$this->setHost( $components[ 'host' ] );
		}
		
		if ( ! empty( $components[ 'port' ] ) ) {
			if ( $this->getScheme() ) {
				if ( $components[ 'port' ] != self::getSchemePort( $this->getScheme() ) ) {
					$this->setPort( $components[ 'port' ] );
				}
			}
		} else {
			if ( $this->getScheme() ) {
				$port = self::getSchemePort( $this->getScheme() );
				
				if ( $port ) {
					$this->setPort( $port );
				}
			}
		}
		
		if ( ! empty( $components[ 'user' ] ) ) {
			if ( ! empty( $components[ 'pass' ] ) ) {
				$this->setUserInfo( $components[ 'user' ] . ":" . $components[ 'pass' ] );
			} else {
				throw new Exception("Database user password not provided.");
				// $this->setUser( $components[ 'user' ] );
			}
		}
		
		if ( ! empty( $components[ 'path' ] ) ) {
			$this->setPath( $components[ 'path' ] );
		}
		
		if ( ! empty( $components[ 'query' ] ) ) {
			$this->setQuery( $components[ 'query' ] );
		}
		
		if ( ! empty( $components[ 'fragment' ] ) ) {
			$this->setFragment( $components[ 'fragment' ] );
		}
	}
	
	/**
	 *
	 * @param string $uri
	 * @throws NetworkException
	 * @return \Wolfgang\Network\Uri\DataUri
	 */
	public static function create ( string $uri ) {
		if ( preg_match( "/^(data)/", $uri ) ) {
			return new DataUri( $uri );
		} else if ( filter_var( $uri, FILTER_VALIDATE_URL ) ) {
			return new Uri( $uri );
		}
		
		throw new NetworkException( "Unable to create URI from '{$uri}'" );
	}
	
	/**
	 *
	 * @param string $scheme
	 */
	private function setScheme ( string $scheme ) {
		$this->scheme = $scheme;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getScheme()
	 */
	public function getScheme ( ): string {
		return $this->scheme;
	}
	
	/**
	 *
	 * @param string $authority
	 */
	private function setAuthority ( string $authority ) {
		$this->authority = $authority;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getAuthority()
	 */
	public function getAuthority ( ): string {
		return $this->authority;
	}
	
	/**
	 *
	 * @param string $user_info
	 */
	private function setUserInfo ( string $user_info ) {
		$this->user_info = $user_info;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getUserInfo()
	 */
	public function getUserInfo ( ): string {
		return $this->user_info;
	}
	
	/**
	 *
	 * @param string $host
	 */
	private function setHost ( string $host ) {
		$this->host = $host;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getHost()
	 */
	public function getHost ( ): string {
		return $this->host;
	}
	
	/**
	 *
	 * @param int $port
	 */
	private function setPort ( int $port ) {
		$this->port = $port;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getPort()
	 */
	public function getPort ( ): ?int {
		return $this->port;
	}
	
	/**
	 *
	 * @param string $path
	 */
	private function setPath ( string $path ) {
		$this->path = $path;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getPath()
	 */
	public function getPath ( ): string {
		return $this->path;
	}
	
	/**
	 *
	 * @param string $query
	 */
	private function setQuery ( string $query ) {
		$this->query = $query;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getQuery()
	 */
	public function getQuery ( ) {
		return $this->query;
	}
	
	/**
	 *
	 * @param string $fragment
	 */
	private function setFragment ( string $fragment ) {
		$this->fragment = $fragment;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IUri::getFragment()
	 */
	public function getFragment ( ): ?string {
		return $this->fragment;
	}
	
	/**
	 * Gets the default scheme used for a particular URI scheme
	 *
	 * @param string $uri_scheme
	 * @return string|NULL
	 */
	public static function getSchemePort ( string $uri_scheme ): ?string {
		if ( empty( $uri_scheme ) ) {
			throw new InvalidArgumentException( "URI scheme not provided" );
		}
		
		switch ( $uri_scheme ) {
			case 'mysql' :
			case 'mysqli' :
				return 3306;
				break;
		}
		
		return null;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::__toString()
	 */
	public function __toString ( ) {
		return $this->uri;
	}
}
