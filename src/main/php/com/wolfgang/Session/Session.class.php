<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\Session\ISession;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\Session\ISessionHandler;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\Model\IUser;
use Wolfgang\Application\Context;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @uses Wolfgang\Session\CookieSessionHandler
 * @uses Wolfgang\Config\Session
 * @since Version 0.1.0
 */
final class Session extends Component implements ISession {

	/**
	 *
	 * @var Session
	 */
	private static $instance;

	/**
	 * The id of this session.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 *
	 * @var string
	 */
	protected $kind;

	/**
	 *
	 * @var ISessionHandler
	 */
	protected $handler;

	/**
	 *
	 * @var IUser
	 */
	protected $user;

	/**
	 *
	 * @param string $kind
	 */
	protected function __construct ( array $options ) {
		parent::__construct();

		if( !isset($options['kind']) ){
			throw new IllegalArgumentException("Session kind session option not provided");
		}

		$this->setKind( $options['kind'] );

		$handler = null;

		switch ( $this->getKind() ) {
			case ISession::KIND_COOKIE :
				if( !isset($options['domain']) ){
					throw new IllegalArgumentException("Session domain session option not provided");
				}

				$handler = new CookieSessionHandler( $options['domain'] );
				break;

			case ISession::KIND_CACHE :
				$handler = new CacheSessionHandler();
				break;

			case ISession::KIND_DATABASE :
				$handler = new DatabaseSessionHandler();
				break;

			case ISession::KIND_FILE :
				$handler = new FileSessionHandler();
				break;
		}

		$this->setHandler( $handler );
		$this->start();
	}


	public static function create( array $options ): Session {
		if ( empty( self::$instance ) ) {
			self::$instance = new Session( $options );
		}
		return self::$instance;
	}

	/**
	 *
	 * @param string $id
	 */
	protected function setId ( $id ) {
		$this->id = $id;
	}

	/**
	 *
	 * @return string
	 */
	public function getId ( ) {
		return $this->id;
	}

	/**
	 *
	 * @param ISessionHandler $handler
	 */
	private function setHandler ( ISessionHandler $handler ) {
		$this->handler = $handler;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Session\ISession::getHandler()
	 */
	public function getHandler ( ): ISessionHandler {
		return $this->handler;
	}

	/**
	 *
	 * @param string $kind
	 */
	private function setKind ( string $kind ) {
		if ( empty( $kind ) ) {
			throw new IllegalArgumentException( 'Session kind must be provided' );
		} else if ( ! in_array( $kind, [ 
				ISession::KIND_COOKIE,
				ISession::KIND_CACHE,
				ISession::KIND_DATABASE,
				ISession::KIND_FILE
		] ) ) {
			throw new InvalidArgumentException( "Session kind '{$kind}' is unknown" );
		}

		$this->kind = $kind;
	}

	/**
	 *
	 * @return string
	 */
	public function getKind ( ): string {
		return $this->kind;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Session\ISession::close()
	 */
	public function close ( ) {
		$this->getHandler()->close();
	}

	/**
	 *
	 * @return boolean
	 */
	public function destroy ( ) {
		return session_destroy();
	}

	/**
	 * @return array
	 */
	public function getAll():array {
		return $_SESSION;
	}

	/**
	 *
	 * @param string $key
	 */
	public function get ( string $key ) {
		if ( isset( $_SESSION[ $key ] ) ) {
			return $_SESSION[ $key ];
		}

		return null;
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set ( string $key, $value ) {
		$_SESSION[ $key ] = $value;
	}

	/**
	 *
	 * @param string $key
	 */
	public function remove ( string $key ) {
		if ( ! empty( $_SESSION[ $key ] ) ) {
			unset( $_SESSION[ $key ] );
		}
	}

	/**
	 * @return void
	 */
	private function start ( ): void {
		if ( php_sapi_name() == 'cli' ) {
			return;
		}

		session_set_save_handler( $this->getHandler(), true );
		session_start( [ 
				'cookie_domain' => Context::getInstance()->getSkin()->getSkinDomain()->getDomain()
		] );
	}
}
