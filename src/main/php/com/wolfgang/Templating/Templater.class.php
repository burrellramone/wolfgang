<?php

namespace Wolfgang\Templating;

use Wolfgang\Application\Context;
use Wolfgang\Application\Application;
use Wolfgang\Interfaces\ITemplater;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Config\App as AppConfig;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @link http://airportruns.ca
 * @since Version 0.1.0
 */
abstract class Templater extends Component implements ISingleton {
	
	/**
	 *
	 * @var ITemplater
	 */
	private static $instance;
	
	/**
	 *
	 * @var array
	 */
	protected $rewrites = [ ];
	
	/**
	 *
	 * @var array
	 */
	protected $layout_directives = [ ];
	
	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * @throws IllegalStateException
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( empty( self::$instance ) ) {
			
			$templater = AppConfig::get( 'templater.type' );
			
			switch ( $templater ) {
				case ITemplater::KIND_SMARTY :
					self::$instance = Smarty::getInstance();
					break;
				default :
					throw new IllegalStateException( "Unknown and unimplemented templater '{$templater}'" );
					break;
			}
			
			self::$instance->assign( 'application', Application::getInstance() );
			self::$instance->assign( 'context', Application::getInstance()->getContext() );
			self::$instance->assign( 'templater', self::$instance );
		}
		return self::$instance;
	}
	
	/**
	 *
	 * @param string $markup
	 * @return string
	 */
	private function cleanMarkup ( string $markup ): string {
		if ( ! Context::getInstance()->isProduction() ) {
			return $markup;
		}
		
		$searches = array (
				"/[\t]{1,}/",
				"/[ ]{2,}/",
				"/<\!--.*?-->/",
				"/\s(\/>)/",
				"/(\n){1,}/"
		);
		
		$replacements = array (
				"",
				" ",
				"",
				"$1",
				""
		);
		
		return preg_replace( $searches, $replacements, $markup );
	}
	
	/**
	 *
	 * @access public
	 * @param array $rewrites
	 */
	public function addTemplateRewrites ( array $rewrites ) {
		foreach ( $rewrites as $rewrite ) {
			$this->addRewrite( $rewrite );
		}
	}
	
	/**
	 *
	 * @param array $rewrite
	 */
	public function addRewrite ( array $rewrite ) {
		$this->rewrites[] = $rewrite;
	}
	
	/**
	 *
	 * @param string $app
	 * @param string $controller
	 * @param string $action
	 * @return string | null
	 */
	public function getRewrite ( $app, $controller, $action ) {
		if ( ! empty( $this->rewrites[ $app ][ $controller ][ $action ] ) ) {
			return TEMPLATE_DIRECTORY . $this->rewrites[ $app ][ $controller ][ $action ][ 'app' ] . "/sections/" . $this->rewrites[ $app ][ $controller ][ $action ][ 'file' ];
		}
	}
	
	/**
	 *
	 * @param array $layout_directives
	 */
	public function setLayoutDirectives ( array $layout_directives ) {
		if ( ! $layout_directives ) {
			throw new IllegalArgumentException( 'Layout directive must be provided' );
		}
		
		$this->layout_directives = $layout_directives;
	}
	
	/**
	 *
	 * @param string $application
	 * @param string $controller
	 * @param string $action
	 * @throws IllegalArgumentException
	 * @return string|null
	 */
	protected function getLayoutFromDirectives ( string $application, string $controller, string $action ): ?string {
		if ( ! $this->layout_directives ) {
			return false;
		} else if ( empty( $application ) ) {
			throw new IllegalArgumentException( "Application must be specified" );
		} else if ( empty( $controller ) ) {
			throw new IllegalArgumentException( "Controller must be specified" );
		} else if ( empty( $action ) ) {
			throw new IllegalArgumentException( "Action must be specified" );
		}
		
		$layout = null;
		$layout_name = @$this->layout_directives[ $application ][ $controller ][ $action ];
		
		if ( $layout_name ) {
			$layout = $application . "/layout/" . $layout_name . '.tmpl';
		}
		
		return $layout;
	}
}
