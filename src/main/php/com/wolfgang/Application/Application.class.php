<?php

namespace Wolfgang\Application;

use Wolfgang\Interfaces\ISingleton;
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
use Wolfgang\Interfaces\Routing\Route\IRoute;
use Wolfgang\Config\Curl as CurlConfig;
use Wolfgang\Interfaces\Application\IContext;
use Wolfgang\Util\Logger\Logger;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Database\DriverManager;
use Wolfgang\ORM\SchemaManager;
use Wolfgang\Network\Uri\Uri;
use Wolfgang\Interfaces\Network\IUri;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Application
 * @since Version 1.0.0
 */
abstract class Application extends Component implements ISingleton , IApplication {

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
	protected $config = [ ];

	protected static $instance;

	/**
	 *
	 * @param string $kind
	 * @param string $name
	 */
	protected function __construct ( string $kind, IContext $context ) {
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

		$this->setSession( SessionManager::getInstance()->getSession() );
		$this->setDispatcher( Dispatcher::getInstance() );
		$this->setEventDispatcher( EventDispatcher::getInstance() );
		$this->setDriverManager( DriverManager::getInstance() );
		$this->setSchemaManager( SchemaManager::getInstance() );

		$this->config = AppConfig::getAll();

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
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( PHP_SAPI == IContext::PHP_SAPI_CLI ) {
			return Cli::getInstance();
		} else if ( preg_match( "/^api\./", $_SERVER[ 'HTTP_HOST' ] ) ) {
			return Api::getInstance();
		} else {
			return Site::getInstance();
		}
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
	protected function getSession ( ): ISession {
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
	 *
	 * @param string $error
	 * @throws InvalidArgumentException
	 */
	public function addError ( string $error ): void {
		if ( empty( $error ) ) {
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
	 */
	public function clearErrors ( ): void {
		$_SESSION[ "errors" ] = $this->errors = array ();
	}

	/**
	 * Add a set of notices to this application
	 *
	 * @param array $notice
	 */
	public function addNotices ( array $notice ): void {
		$this->notices = array_merge( $this->notices, $notice );
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
	 */
	public function clearNotices ( ): void {
		$_SESSION[ "notices" ] = $this->notices = array ();
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
	 */
	public function clearWarnings ( ): void {
		$_SESSION[ "warnings" ] = $this->warnings = array ();
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
	abstract public function redirect ( IUri $uri ): void;

	/**
	 *
	 * @param callable $callable
	 */
	private function registerShutdownFunction ( ): void {
		call_user_func_array( 'register_shutdown_function', func_get_args() );
	}

	/**
	 *
	 * @return void
	 */
	private function bootstrap ( ): void {
		set_error_handler( function ( $errno, $errstr, $errfile = null, $errline = null, array $errcontext = null) {
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

		$log_directory = AppConfig::get( 'directories.log_directory' );
		$temporary_directory = AppConfig::get( 'directories.temporary_directory' );
		$templating_temporary_directory = AppConfig::get( 'directories.templating_temporary_directory' );
		$xhprof_temporary_directory = AppConfig::get( 'directories.xhprof_temporary_directory' );
		$uploads_temporary_directory = AppConfig::get( 'directories.uploads_temporary_directory' );
		$curl_temporary_directory = CurlConfig::get( 'temporary_directory' );

		if ( ! Filesystem::exists( $log_directory ) ) {
			Filesystem::makeDirectory( $log_directory, 0777 );
			exec( "chown www-data:www-data -R " . $log_directory );
		}

		if ( ! Filesystem::exists( $temporary_directory ) ) {
			Filesystem::makeDirectory( $temporary_directory, 0777 );
			exec( "chown www-data:www-data -R " . $temporary_directory );
		}

		if ( ! Filesystem::exists( $templating_temporary_directory ) ) {
			Filesystem::makeDirectory( $templating_temporary_directory, 0777 );
			exec( "chown www-data:www-data -R " . $templating_temporary_directory );
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

		exec( "chmod 777 -R " . $temporary_directory );

		$this->registerShutdownFunction( function ( $application ) {
			// Rollback all transactions that might have been left open
			foreach ( $application->getDriverManager()->getConnections() as $connection ) {
				$connection->rollback();
			}
		}, $this );
	}
}
