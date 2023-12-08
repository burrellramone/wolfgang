<?php

namespace Wolfgang\Templating;

use Wolfgang\Interfaces\ITemplater;
use Wolfgang\Exceptions\Templating\TemplateNotExistException;
use Wolfgang\Exceptions\Templating\Exception as TemplatingException;
use Wolfgang\Util\Filesystem;
use Wolfgang\Exceptions\Filesystem\FileNotExistException;
use Wolfgang\Application\Context;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Config\App as AppConfig;
use Wolfgang\Application\Application;
use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Smarty extends Templater implements ITemplater {

	/**
	 *
	 * @var Smarty
	 */
	private static $instance;

	/**
	 *
	 * @var \Smarty
	 */
	private $smarty;

	/**
	 *
	 * @var string
	 */
	private $layout;

	/**
	 *
	 * @var string
	 */
	private $template;

	protected function __construct ( ) {
		$this->smarty = new \Smarty();

		parent::__construct();
	}

	/**
	 *
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( empty( self::$instance ) ) {
			self::$instance = new Smarty();
		}
		return self::$instance;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Component::init()
	 */
	protected function init ( ) {
		parent::init();

		$this->setSmartyLocations();
		$this->enableCaching();
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::assign()
	 */
	public function assign ( string $name, $val ): void {
		$this->smarty->assign( $name, $val );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::display()
	 */
	public function display ( ): void {
		$context = Application::getInstance()->getContext();

		if ( ! empty( $this->getLayout() ) && ! preg_match( "/^edit_tab_/", $context->getAction() ) && ! preg_match( "/^view_tab_/", $context->getAction() ) ) {
			echo $this->cleanMarkup( $this->smarty->fetch( $this->getLayout() ) );
		} else {
			echo $this->cleanMarkup( $this->smarty->fetch( $this->template ) );
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::fetch()
	 */
	public function fetch ( $template = null): string {
		if ( ! empty( $template ) ) {
			if ( ! Filesystem::exists( $template ) ) {
				throw new FileNotExistException( "Template {$template} does not exist" );
			}

			return $this->smarty->fetch( $template );
		}

		$context = Application::getInstance()->getContext();

		if ( preg_match( "/^edit_tab_/", $context->getAction() ) || preg_match( "/^view_tab_/", $context->getAction() ) ) {
			return $this->cleanMarkup( $this->smarty->fetch( $this->template ) );
		} else if ( ! empty( $this->layout ) ) {
			return $this->cleanMarkup( $this->smarty->fetch( $this->getLayout() ) );
		}

		return '';
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::setLayout()
	 */
	public function setLayout ( string $layout ) {
		if ( empty( $layout ) ) {
			throw new TemplatingException( "Unable to set layout. Layout is empty" );
		}

		$this->layout = TEMPLATE_DIRECTORY . $layout;
	}

	/**
	 *
	 * @return string|NULL
	 */
	public function getLayout ( ): ?string {
		return $this->layout;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::setTemplate()
	 */
	public function setTemplate ( string $path ) {
		if ( ! $path ) {
			throw new IllegalArgumentException( "Template path must be provided" );
		} else if ( ! $this->templateExists( $path ) ) {
			throw new TemplatingException( "File path must be provided" );
		}

		$this->template = TEMPLATE_DIRECTORY . $path;
		$this->assign( "template", $this->template );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::getTemplate()
	 */
	public function getTemplate ( ) {
		return $this->template;
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
	 * Enables caching of compiled templates conditionally. If the current environment, as described
	 * by the environment variable APPLICATION_ENV, is 'production' then caching will be enabled
	 * else it will not.
	 *
	 * @return void
	 */
	private function enableCaching ( ): void {
		if ( Context::getInstance()->isProduction() ) {
			$this->smarty->caching = 2;
			// $this->smarty->caching = 2;
			// set the cache_lifetime -1 so that cached files never expire
			// $this->smarty->cache_lifetime = - 1;
		} else {
			// Disable caching. Templates will always be recompiled
			$this->smarty->caching = 0;
		}
	}

	/**
	 *
	 * @return void
	 */
	private function setSmartyLocations ( ): void {
		if ( php_sapi_name() != 'cli' ) {
			$this->smarty->addTemplateDir( TEMPLATE_DIRECTORY );
			$context = Context::getInstance();
			$skin = $context->getSkin();
			$skin_name = $skin->getName();
			$temporary_directory = AppConfig::get( 'directories.temporary_directory' );

			$this->smarty->setCompileDir( "{$temporary_directory}smarty/templates_c/{$skin_name}" );
			$this->smarty->setCacheDir( "{$temporary_directory}smarty/cache/{$skin_name}" );
			$this->smarty->setConfigDir( "{$temporary_directory}smarty/configs/{$skin_name}" );
			
		}
	}

	/**
	 */
	public function determineLayout ( ): void {
		$context = Application::getInstance()->getContext();
		$templater = Templater::getInstance();
		$skin = Context::getInstance()->getSkin();

		$layout = null;
		$common_default_layout = "Common/layout/default.tmpl";
		$default_layout = $skin->getName() . "/layout/default.tmpl";

		$layout = $this->getLayoutFromDirectives( $skin->getName(), $context->getControllerName(), $context->getAction() );

		if ( ! $layout ) {
			$layout = $default_layout;
		}

		if ( ! $templater->templateExists( $layout ) ) {
			$layout = $common_default_layout;
		}

		$this->setLayout( $layout );
		$this->determineTemplate();
	}

	/**
	 *
	 * @throws TemplateNotExistException
	 */
	private function determineTemplate ( ) {
		$context = Application::getInstance()->getContext();
		$app = $context->getSkin()->getName();

		$controller = $context->getControllerName();
		$action = $context->getAction();

		$template = $app . "/sections/{$controller}/{$action}.tmpl";

		if ( ! $this->templateExists( $template ) && $action === 'add' ) {
			$template = preg_replace( "/add\.tmpl/", "edit.tmpl", $template );
		}

		if ( ! $this->templateExists( $template ) ) {
			// See if there is a template rewrite for this application/controller/action
			$temp = self::getRewrite( $app, $controller, $action );

			if ( empty( $temp ) || ! $this->templateExists( $temp ) ) {
				$template = preg_replace( "/($app)/", "Common", $template, 1 );

				if ( ! $this->templateExists( $template ) ) {
					throw new TemplateNotExistException( "Template file `{$template}` does not exist" );
				}
			} else {
				$template = $temp;
			}
		}

		$this->setTemplate( $template );

		if ( $action == 'add' ) {
			$action = 'edit';
		}

		// Determine CSS Stylesheet
		// Seek minified version first
		$stylesheet = PUBLIC_DIRECTORY . 'css/' . $app . '/' . 'sections/' . $controller . '/' . $action . '.min.css';

		if ( ! file_exists( $stylesheet ) ) {
			$stylesheet = PUBLIC_DIRECTORY . 'css/' . $app . '/' . 'sections/' . $controller . '/' . $action . '.css';

			if ( ! file_exists( $stylesheet ) ) {
				$stylesheet = str_replace( $app, 'Common', $stylesheet );
			}
		}

		// Determine Script File
		$script_file = PUBLIC_DIRECTORY . 'js/' . $app . '/' . 'sections/' . $controller . '/' . $action . '.js';
		$script_file_min = PUBLIC_DIRECTORY . 'js/' . $app . '/' . 'sections/' . $controller . '/' . $action . '.min.js';
		
		if ($context->isProduction() && file_exists($script_file_min)) {
		    $script_file = $script_file_min;
		}
		
		if ( ! file_exists( $script_file ) ) {
		    $script_file = str_replace( $app, 'Common', $script_file );
		}

		if ( file_exists( $stylesheet ) ) {
			$stylesheet = str_replace( PUBLIC_DIRECTORY, "/", $stylesheet );
			$this->assign( 'stylesheet', $stylesheet );
		} else {
			// Defailt Stylesheet
			$this->assign( 'stylesheet', WOLFGANG_RESOURCES_DIR . 'css/stylesheet-default.css' );
		}

		if ( file_exists( $script_file ) ) {
			$script_file = str_replace( PUBLIC_DIRECTORY, "/", $script_file );
			$this->assign( 'script_file', $script_file );
		} else {
			$this->assign( 'script_file', '#' );
		}
	}

	/**
	 *
	 * @param string $path
	 * @throws IllegalArgumentException
	 * @return bool
	 */
	public function templateExists ( string $path ) {
		if ( empty( $path ) ) {
			throw new IllegalArgumentException( "File path must be provided" );
		}

		return $this->smarty->templateExists( $path );
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Templating\Templater::setLayoutDirectives()
	 */
	public function setLayoutDirectives ( array $layout_directives ) {
		$this->layout_directives = $layout_directives;
	}
}
