<?php

namespace Wolfgang\Templating;

use Wolfgang\Interfaces\ITemplater;
use Wolfgang\Interfaces\Routing\Route\ISiteRoute;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Interfaces\ISingleton;

/**
 *
 * @package Wolfgang\Temlating
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Twig extends Templater implements ITemplater {
	
	/**
	 *
	 * @var ISingleton
	 */
	private static $instance;
	
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
	
	/**
	 */
	protected function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( empty( self::$instance ) ) {
			self::$instance = new Twig();
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
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::assign()
	 */
	public function assign ( string $name, $val ): void {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::display()
	 */
	public function display ( ): void {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::fetch()
	 */
	public function fetch ( $template = null): string {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::setLayout()
	 */
	public function setLayout ( string $layout ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::setTemplate()
	 */
	public function setTemplate ( string $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\ITemplater::getTemplate()
	 */
	public function getTemplate ( ) {
	}
	
	public function determineLayout ( ISiteRoute $route ) {
	}
	
	private function determineTemplate ( ISiteRoute $route ) {
	}
	
	/**
	 *
	 * @param string $path
	 * @throws IllegalArgumentException
	 * @return bool
	 */
	public function templateExists ( string $path ) {
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Templating\Templater::setLayoutDirectives()
	 */
	public function setLayoutDirectives ( array $layout_directives ) {
	}
}
