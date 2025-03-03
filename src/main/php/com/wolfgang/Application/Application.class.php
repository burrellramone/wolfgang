<?php

namespace Wolfgang\Application;

use Wolfgang\Interfaces\Application\IApplication;
use Wolfgang\Interfaces\Routing\IRouter;
use Wolfgang\Interfaces\Dispatching\IDispatcher;
use Wolfgang\Interfaces\Dispatching\IEventDispatcher;
use Wolfgang\Interfaces\Message\IRequest;
use Wolfgang\Interfaces\Message\IResponse;
use Wolfgang\Interfaces\Session\ISession;
use Wolfgang\Session\Manager as SessionManager;
use Wolfgang\Dispatching\Dispatcher;
use Wolfgang\Dispatching\EventDispatcher;
use Wolfgang\Util\Filesystem;
use Wolfgang\Config\App as AppConfig;
use Wolfgang\Interfaces\Routing\IRoute;
use Wolfgang\Exceptions\Exception;
use Wolfgang\Config\Curl as CurlConfig;
use Wolfgang\Config\Session as SessionConfig;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Util\Logger\Logger;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Database\DriverManager;
use Wolfgang\ORM\SchemaManager;
use Wolfgang\Network\Uri\Uri;
use Wolfgang\Interfaces\Network\IUri;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
abstract class Application extends Component implements IApplication {

	/**
	 *
	 * @see IApplication
	 * @var string
	 */
	protected $kind;

	/**
	 *
	 * @see IApplication
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @var IRouter
	 */
	protected $router;

	/**
	 *
	 * @var IDispatcher
	 */
	protected $dispatcher;

	/**
	 *
	 * @var IEventDispatcher
	 */
	protected $event_dispatcher;

	/**
	 *
	 * @var DriverManager
	 */
	protected $driver_manager;

	/**
	 *
	 * @var SchemaManager
	 */
	protected $schema_manager;

	/**
	 *
	 * @var IRequest
	 */
	protected $request;

	/**
	 *
	 * @var IResponse
	 */
	protected $response;

	/**
	 *
	 * @var ISession
	 */
	protected $session;

	/**
	 *
	 * @var IContext
	 */
	protected $context;

	/**
	 *
	 * @var Uri
	 */
	private $profile_run_uri;

	/**
	 * @var string
	 */
	private $temporary_directory;

	/**
	 *
	 * @var array
	 */
	protected $notices = [ ];
	/**
	 *
	 * @var array
	 */
	protected $warnings = [ ];
	/**
	 *
	 * @var array
	 */
	protected $errors = [ ];

	/**
	 * The configuration loaded from the 'app' configuration group
	 *
	 * @var array
	 */
	protected array $config = [ ];

	protected static $instance;

	/**
	 *
	 * @param string $kind
	 * @param IContext $context
	 */
	public function __construct ( string $kind, IContext $context ) {
		$this->setKind( $kind );
		$this->setContext($context);

		parent::__construct();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->config = AppConfig::getAll();

		if(!$this->context->isCli()){
			$kind = SessionConfig::get( 'kind' );
			$domain = $this->context->getSkin()->getSkinDomain()->getDomain();
			$session = SessionManager::getInstance()->createSession( $kind, array(
				'domain' => $domain,
				'id_prefix' => $this->config['session_id_prefix']
			) );

			if($session->get('remember_me')){
				$session->setExpires(YEAR_IN_SECONDS);
			}

			$this->setSession( $session );
		} else {
			//Cli session
			$session = SessionManager::getInstance()->createSession(ISession::KIND_CLI);
			$this->setSession( $session );
		}
		
		$this->setDispatcher( Dispatcher::getInstance() );
		$this->setEventDispatcher( EventDispatcher::getInstance() );
		$this->setDriverManager( DriverManager::getInstance() );
		$this->setSchemaManager( SchemaManager::getInstance() );

		$this->bootstrap();
	}

	protected function onBeforeExec ( ): void {
	}

	protected function onAfterExec ( ): void {
		$this->clearErrors();
		$this->clearNotices();
		$this->clearWarnings();
	}

	/**
	 *
	 * @return IApplication
	 */
	public static function getInstance ( ): IApplication {
		if(self::$instance){
			return self::$instance;
		}

		throw new Exception("Application not instantiated.");
	}

	/**
	 *
	 * @param string $kind
	 */
	protected function setKind ( $kind ) {
		$this->kind = $kind;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::getKind()
	 */
	public function getKind ( ): string {
		return $this->kind;
	}

	/**
	 *
	 * @param string $name
	 */
	protected function setName ( string $name ) {
		$this->name = $name;
	}

	/**
	 *
	 * @return string
	 */
	public function getVersion ( ): string {
		return $this->config[ 'version' ];
	}

	/**
	 *
	 * @return bool
	 */
	public function isProfiling ( ): bool {
		return ( bool ) $this->config[ 'profiling' ];
	}

	/**
	 *
	 * @return bool
	 */
	public function isJournaling ( ): bool {
		return ( bool ) $this->config[ 'journaling' ];
	}

	/**
	 * @return array
	 */
	public function getConfig() : array {
		if(!$this->config){
			$this->config = AppConfig::getAll();
		}

		return $this->config;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Application\IApplication::getName()
	 */
	public function getName ( ): string {
		return $this->getContext()->getSkin()->getName();
	}

	/**
	 *
	 * @param IRouter $router
	 */
	protected abstract function setRouter ( IRouter $router );

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Application\IApplication::getRouter()
	 */
	public function getRouter ( ): IRouter {
		return $this->router;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Application\IApplication::addRoute()
	 */
	public function addRoute ( IRoute $route ) {
		$this->getRouter()->addRoute( $route );
	}

	/**
	 *
	 * @param IDispatcher $dispatcher
	 */
	protected function setDispatcher ( IDispatcher $dispatcher ) {
		$dispatcher->setApplication( $this );
		$this->dispatcher = $dispatcher;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::getDispatcher()
	 */
	public function getDispatcher ( ): Dispatcher {
		return $this->dispatcher;
	}

	/**
	 *
	 * @param IEventDispatcher $event_dispatcher
	 */
	protected function setEventDispatcher ( IEventDispatcher $event_dispatcher ) {
		$this->event_dispatcher = $event_dispatcher;
	}

	/**
	 *
	 * @return IEventDispatcher
	 */
	public function getEventDispatcher ( ): IEventDispatcher {
		return $this->event_dispatcher;
	}

	/**
	 *
	 * @param DriverManager $driver_manager
	 */
	private function setDriverManager ( DriverManager $driver_manager ) {
		$this->driver_manager = $driver_manager;
	}

	/**
	 *
	 * @return DriverManager
	 */
	public function getDriverManager ( ): DriverManager {
		return $this->driver_manager;
	}

	/**
	 *
	 * @param SchemaManager $schema_manager
	 */
	private function setSchemaManager ( SchemaManager $schema_manager ) {
		$this->schema_manager = $schema_manager;
	}

	/**
	 *
	 * @return SchemaManager
	 */
	public function getSchemaManager ( ): SchemaManager {
		return $this->schema_manager;
	}

	/**
	 *
	 * @param IRequest $request
	 */
	protected abstract function setRequest ( IRequest $request );

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Application\IApplication::getRequest()
	 */
	public function getRequest ( ): IRequest {
		return $this->request;
	}

	/**
	 *
	 * @param IResponse $response
	 */
	protected abstract function setResponse ( IResponse $response );

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Wolfgang\Interfaces\Application\IApplication::getResponse()
	 */
	public function getResponse ( ): IResponse {
		return $this->response;
	}

	/**
	 *
	 * @param ISession $session
	 */
	private function setSession ( ISession $session ) {
		$this->session = $session;
	}

	/**
	 *
	 * @return ISession
	 */
	public function getSession ( ): ISession {
		return $this->session;
	}

	/**
	 *
	 * @param IContext $context
	 */
	private function setContext ( IContext $context ) {
		$this->context = $context;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::getContext()
	 */
	public function getContext ( ): IContext {
		return $this->context;
	}

	/**
	 *
	 * @return string
	 */
	public function getProfileNamespace ( ): string {
		if ( $this->getKind() == IApplication::KIND_CLI ) {
			return 'application.' . $this->getKind();
		}

		return 'application.' . $this->getKind() . '.' . strtolower( $this->getContext()->getSkinDomain()->getSkin()->getName() );
	}

	/**
	 *
	 * @param Uri $profile_run_uri
	 */
	public function setProfileRunUri ( Uri $profile_run_uri ) {
		$this->profile_run_uri = $profile_run_uri;
	}

	/**
	 *
	 * @return Uri|NULL
	 */
	public function getProfileRunUri ( ): ?Uri {
		return $this->profile_run_uri;
	}

	/**
	 *
	 * @return string
	 */
	public function getProfileRunLink ( ): string {
		$uri = $this->getProfileRunUri();

		if ( ! $uri ) {
			$uri = "#";
		}
		
		return "<a href='{$uri}' target='_blank' class='xhprof animated flash'>Xhprof Run</a>";
	}

	/**
	 *
	 * @param array $errors
	 * @return void
	 */
	public function addErrors ( array $errors ): void {
		$this->errors = array_merge( $this->errors, $errors );
	}

	/**
	 * @param $errors
	 * @return void
	 */
	public function setErrors( array $errors ):void {
		$this->errors = array();
		$this->addErrors($errors);
	}

	/**
	 *
	 * @param string $error
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function addError ( string $error ): void {
		if ( ! $error ) {
			throw new InvalidArgumentException( "Error not provided" );
		}

		$this->errors[] = $error;
	}

	/**
	 * Gets all existing errors within this application
	 *
	 * @return array
	 */
	public function getErrors ( ): array {
		return $this->errors;
	}

	/**
	 * Clears all existing errors within this application
	 * 
	 * @return void
	 */
	public function clearErrors ( ): void {
		$session = $this->getSession();
		$this->errors = array();

		if($session){
			$session->set('errors', array());
		}
	}

	/**
	 * Add a set of notices to this application
	 *
	 * @param array $notices
	 */
	public function addNotices ( array $notices ): void {
		$this->notices = array_merge( $this->notices, $notices );
	}

	/**
	 * @param $notices
	 * @return void
	 */
	public function setNotices( array $notices ):void {
		$this->notices = array();
		$this->addNotices($notices);
	}

	/**
	 * Add a notice to this application
	 *
	 * @param string $notice
	 * @throws InvalidArgumentException
	 */
	public function addNotice ( string $notice ): void {
		if ( empty( $notice ) ) {
			throw new InvalidArgumentException( "Notice not provided" );
		}
		$this->notices[] = $notice;
	}

	/**
	 * Clears all existing notices within this application
	 * 
	 * @return void
	 */
	public function clearNotices ( ): void {
		$session = $this->getSession();
		$this->notices = array();
		
		if($session){
			$session->set('notices', array());
		}
	}

	/**
	 * Get all existing notices within this application
	 *
	 * @return array
	 */
	public function getNotices ( ): array {
		return $this->notices;
	}

	/**
	 * Adds a set of warnings to this application
	 *
	 * @param array $warnings
	 */
	public function addWarnings ( array $warnings ): void {
		$this->warnings = array_merge( $this->warnings, $warnings );
	}

	/**
	 * @param $warnings
	 * @return void
	 */
	public function setWarnings( array $warnings ):void {
		$this->warnings = array();
		$this->addWarnings($warnings);
	}

	/**
	 * Adds a warning to this application
	 *
	 * @param string $warning
	 * @throws InvalidArgumentException
	 */
	public function addWarning ( string $warning ): void {
		if ( empty( $warning ) ) {
			throw new InvalidArgumentException( "Warning not provided" );
		}
		$this->warnings[] = $warning;
	}

	/**
	 * Clears all existing warnings within this application
	 * 
	 * @return void
	 */
	public function clearWarnings ( ): void {
		$session = $this->getSession();
		$this->warnings = array();
		
		if($session){
			$session->set('warnings', array());
		}
	}

	/**
	 * Gets all warnings within this application
	 *
	 * @return array
	 */
	public function getWarnings ( ): array {
		return $this->warnings;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Application\IApplication::redirect()
	 */
	abstract public function redirect ( IUri|string $uri ): void;

	/**
	 *
	 * @param callable $callable
	 */
	private function registerShutdownFunction ( ): void {
		call_user_func_array( 'register_shutdown_function', func_get_args() );
	}

	/**
	 * Logs a message as an error
	 * 
	 * @param mixed $message The message to log
	 * @return void
	 */
	protected function logError(mixed $message):void {
		Logger::getLogger()->error( $message );
	}

	/**
	 *
	 * @return void
	 */
	private function bootstrap ( ): void {
		set_error_handler( function ( $errno, $errstr, string|null $errfile = null, string|null $errline = null, array|null $errcontext = null) {
			if ( ! empty( $_SERVER[ 'HTTP_HOST' ] ) ) {
				$host = $_SERVER[ 'HTTP_HOST' ];
			} else {
				$host = gethostname();
			}

			Logger::getLogger()->error( $errstr, [ 
					'level' => $errno,
					'message' => $errstr,
					'file' => $errfile,
					'line' => $errline,
					'host' => $host,
					'application' => $this->getName()
			] );
		}, E_ALL );

		$this->temporary_directory = TEMP_DIRECTORY;
		$log_directory = LOG_DIRECTORY;
		$xhprof_temporary_directory = XHPROF_DIRECTORY;
		$uploads_temporary_directory = UPLOADS_DIRECTORY;
		$curl_temporary_directory = CURL_DIRECTORY;

		if ( ! Filesystem::exists( $this->temporary_directory ) ) {
			Filesystem::makeDirectory( $this->temporary_directory, 0777 );
			exec( "chown www-data:www-data -R " . $this->temporary_directory );
		}

		if ( ! Filesystem::exists( $log_directory ) ) {
			Filesystem::makeDirectory( $log_directory, 0777 );
			exec( "chown www-data:www-data -R " . $log_directory );
		}

		if ( ! Filesystem::exists( $xhprof_temporary_directory ) ) {
			Filesystem::makeDirectory( $xhprof_temporary_directory, 0777 );
			exec( "chown www-data:www-data -R " . $xhprof_temporary_directory );
		}

		if ( ! Filesystem::exists( $uploads_temporary_directory ) ) {
			Filesystem::makeDirectory( $uploads_temporary_directory, 0777 );
			exec( "chown www-data:www-data -R " . $uploads_temporary_directory );
		}

		if ( ! Filesystem::exists( $curl_temporary_directory ) ) {
			Filesystem::makeDirectory( $curl_temporary_directory, 0777 );
			exec( "chown www-data:www-data -R " . $curl_temporary_directory );
		}

		exec( "chmod 777 -R " . $this->temporary_directory );

		$this->registerShutdownFunction( function ( $application ) {
			// Rollback all transactions that might have been left open
			foreach ( $application->getDriverManager()->getConnections() as $connection ) {
				$connection->rollback();
			}
		}, $this );
	}

	/**
	 * @var 
	 */
	public function getTemporaryDirectory():string {
		return $this->temporary_directory;
	}

	public function __destruct ( ) {
		parent::__destruct();

		$this->getSession()->close();
	}
}
