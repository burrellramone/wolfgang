<?php

namespace Wolfgang\Util\Logger;

use Wolfgang\Interfaces\Logger\ILogger;
use Wolfgang\Interfaces\Logger\IFileLogger;
use Wolfgang\Config\Logger as LoggerConfig;
use Wolfgang\Util\Logger\Configuration\FileLoggerConfiguration;
use Wolfgang\Date\DateTime;
use Wolfgang\Util\Filesystem;
use Wolfgang\Application\Application;
use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
final class FileLogger extends Logger implements IFileLogger {
	
	/**
	 *
	 * @var FileLoggerConfiguration
	 */
	protected $configuration;
	
	/**
	 *
	 * @param array $configuration
	 */
	protected function __construct ( FileLoggerConfiguration $configuration ) {
		parent::__construct();
		
		$this->setConfiguration( $configuration );
	}
	
	/**
	 *
	 * @param string $name
	 * @return ILogger
	 */
	public static function getLogger ( string $name = null): ILogger {
		$logger = null;
		
		if ( $name == null ) {
			$configurations = LoggerConfig::get( 'file' );
			$logger = new FileLogger( new FileLoggerConfiguration( array_shift( $configurations ) ) );
		} else {
			throw new Exception();
		}
		
		return $logger;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::debug()
	 */
	public function debug ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_DEBUG, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::critical()
	 */
	public function critical ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_CRITICAL, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::alert()
	 */
	public function alert ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_ALERT, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::emergency()
	 */
	public function emergency ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_EMERGENCY, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::warning()
	 */
	public function warning ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_WARNING, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::error()
	 */
	public function error ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_ERROR, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::notice()
	 */
	public function notice ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_NOTICE, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::info()
	 */
	public function info ( $message, array $context = []) {
		$this->log( ILogger::LEVEL_INFO, $message, $context );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\ILogger::log()
	 */
	public function log ( $level, $message, array $context = []) {
		$file = null;
		$line = null;
		
		if ( ($message instanceof \Exception) || ($message instanceof \Error) || ($message instanceof \ErrorException) ) {
			$file = $message->getFile();
			$line = $message->getLine();
			$message = $message->getMessage();
		} else if ( ! empty( $context ) ) {
			if ( ! empty( $context[ 'file' ] ) ) {
				$file = $context[ 'file' ];
			}
			
			if ( ! empty( $context[ 'line' ] ) ) {
				$line = $context[ 'line' ];
			}
			
			if ( ! empty( $context[ 'host' ] ) ) {
				$host = $context[ 'host' ];
			}
			
			if ( ! empty( $context[ 'application' ] ) ) {
				$application = $context[ 'application' ];
			}
			
			if ( ! empty( $context[ 'ip_address' ] ) ) {
				$ip_address = $context[ 'ip_address' ];
			}
		}
		
		$host_ip_address = gethostbyname( gethostname() );
		
		if ( empty( $host ) ) {
			if ( ! empty( $_SERVER[ 'HTTP_HOST' ] ) ) {
				$host = $_SERVER[ 'HTTP_HOST' ];
			} else {
				$host = gethostname();
			}
		}
		
		if ( empty( $application ) ) {
			if ( php_sapi_name() == 'cli' ) {
				$application = 'cli';
			} else {
				$application = Application::getInstance()->getName();
			}
		}
		
		if ( empty( $ip_address ) ) {
			if ( $application != 'cli' ) {
				$ip_address = $_SERVER[ 'REMOTE_ADDR' ];
			} else {
				$ip_address = 'N/A';
			}
		}
		
		$error = [ 
				'timestamp' => '[' . new DateTime() . ']',
				'level' => '[' . $level . ']',
				'host' => '[' . $host . ']',
				'host_ip_address' => '[' . $host_ip_address . ']',
				'application' => '[' . $application . ']',
				'remote_ip_address' => '[' . $ip_address . ']',
				'request' => $application == 'cli' ? '[N/A]' : '[' . $_SERVER[ 'REQUEST_METHOD' ] . ' ' . $_SERVER[ 'REQUEST_URI' ] . ']',
				'message' => '[' . $message . ']',
				'file' => '[' . $file . ']',
				'line' => '[' . $line . ']'
		];
		
		if ( ! Filesystem::exists( $this->configuration->getFilepath() ) ) {
			Filesystem::create( $this->configuration->getFilepath(), null, null, 0666 );
		}
		
		Filesystem::putContents( $this->configuration->getFilepath(), "\n" . implode( ' ', $error ), FILE_APPEND );
	}
	
	/**
	 *
	 * @param FileLoggerConfiguration $configuration
	 */
	private function setConfiguration ( FileLoggerConfiguration $configuration ) {
		$this->configuration = $configuration;
	}
}