<?php

namespace Wolfgang\Session;

use Wolfgang\Interfaces\Session\ISession;
use Wolfgang\Util\Cookie;
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
	 * @var string
	 */
	private string $id_prefix = 'WG';

	/**
	 *
	 * @var string
	 */
	protected $kind;

	/**
	 *
	 * @var string
	 */
	protected string $domain;

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
	 * @param array $options An array of options to use in creating the session
	 */
	public function __construct ( array $options ) {
		parent::__construct();

		if( !isset($options['kind']) ){
			throw new IllegalArgumentException("Session kind session option not provided");
		}

		if( !isset($options['domain']) ){
			throw new IllegalArgumentException("Session domain session option not provided");
		}

		$this->setKind( $options['kind'] );
		$this->setDomain( $options['domain'] );

		if(!empty($options['id_prefix'])){
			$this->setIdPrefix($options['id_prefix']);
		}

		switch ( $this->getKind() ) {
			case ISession::KIND_COOKIE :
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
	protected function setId ( string $id ) {
		if(!$id){
			throw new InvalidArgumentException('id');
		}

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
	 * @see https://www.php.net/manual/function.session-name.php
	 * @return string
	 */
	public function getName():string {
		return session_name();
	}

   /**
	* @param string
	*/
   public function setIdPrefix(string $id_prefix):void {
	   $this->id_prefix = $id_prefix;
   }

	/**
	 * @return string
	 */
	public function getIdPrefix():string {
		return $this->id_prefix;
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
	 * @param string $domain
	 * @return void
	 */
	private function setDomain ( string $domain ):void {
		$this->domain = $domain;
	}

	/**
	 * @return string
	 */
	public function getDomain():string {
		return $this->domain;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Session\ISession::close()
	 */
	public function close ( ) {
		//Don't encrypt session id value
		Cookie::write($this->getName(), $this->getId(), $this->getExpires(), '/', $this->getDomain(), true, true, false );

		$this->getHandler()->close();
	}

	/**
	 *
	 * @return boolean
	 */
	public function destroy ( ) {
		session_destroy();
		
		Cookie::write($this->getName(), 'deleted', -1, '/', $this->getDomain() );

		return $this->getHandler()->destroy($this->getId());
	}

	/**
	 * @return array
	 */
	public function getAll():array {
		return $_SESSION;
	}

	/**
	 *
	 * @param mixed $key
	 */
	public function get ( string $key ):mixed {
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
	 * @param int
	 */
	public function setExpires(int $expires):void{
		$this->getHandler()->setExpires($expires);
	}

	/**
	 * @return int
	 */
	public function getExpires():int {
		return $this->getHandler()->getExpires();
	}

	/**
	 * @see https://www.php.net/manual/en/session.configuration.php
	 * @return void
	 */
	private function start ( ): void {
		if ( php_sapi_name() == 'cli' ) {
			return;
		}

		if(!isset($_COOKIE[$this->getName()])){
			$id = session_create_id($this->getIdPrefix());
			session_id($id);
		}

		session_set_save_handler( $this->getHandler(), true );
		session_start( [ 
			'cookie_domain' => $this->getDomain(),
			'cookie_lifetime' => 0,
			'gc_probability' => 1,
			'gc_divisor' => 100,
		] );

		$this->setId(session_id());
	}
}
