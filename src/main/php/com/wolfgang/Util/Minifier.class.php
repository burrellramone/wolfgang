<?php

namespace Wolfgang\Util;

use MatthiasMullie\Minify;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Exceptions\Filesystem\Exception as FilesystemException;

/**
 *
 * @package Wolfgang\Util
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class Minifier extends Component implements ISingleton {
	private $scripts = array ();
	
	/**
	 *
	 * @var array
	 */
	private $stylesheets = array ();
	
	/**
	 *
	 * @var Minifier
	 */
	protected static $instance;
	
	/**
	 *
	 * @param string $persistent_id
	 */
	public function __construct ( ) {
		parent::__construct();
	}
	
	/**
	 *
	 * @return ISingleton
	 */
	public static function getInstance ( ): ISingleton {
		if ( empty( self::$instance ) ) {
			self::$instance = new Minifier();
		}
		return self::$instance;
	}
	
	public function minifyScripts ( ) {
		foreach ( $this->scripts as $script_directory_specifications ) {
			$files = glob( DOCUMENT_ROOT . $script_directory_specifications[ 'input_directory' ] . "*.js" );
			foreach ( $files as $file_path ) {
				$file_basename = basename( $file_path );
				$file_output_basename = str_replace( ".js", ".min.js", $file_basename );
				$output_directory = DOCUMENT_ROOT . $script_directory_specifications[ 'output_directory' ];
				$file_output_path = $output_directory . $file_output_basename;
				
				if ( ! file_exists( $output_directory ) ) {
					if ( ! mkdir( $output_directory ) ) {
						throw new FilesystemException( "Unable to create output directory '{$output_directory}'." );
					}
				}
				
				if ( file_exists( $file_output_path ) ) {
					// Check if the unminified file has been modified since the minified one has
					// been created
					if ( Filesystem::getModifiedDateTime( $file_output_path ) > Filesystem::getModifiedDateTime( $file_path ) ) {
						continue;
					}
				}
				
				$minifier = new Minify\JS( $file_path );
				$minifier->minify( $file_output_path );
			}
		}
		return $this;
	}
	
	public function minifyStylesheets ( ) {
		foreach ( $this->stylesheets as $stylesheet_directory_specifications ) {
			$files = glob( DOCUMENT_ROOT . $stylesheet_directory_specifications[ 'input_directory' ] . "*.css" );
			foreach ( $files as $file_path ) {
				$file_basename = basename( $file_path );
				$file_output_basename = str_replace( ".css", ".min.css", $file_basename );
				$output_directory = DOCUMENT_ROOT . $stylesheet_directory_specifications[ 'output_directory' ];
				$file_output_path = $output_directory . $file_output_basename;
				
				if ( ! file_exists( $output_directory ) ) {
					if ( ! mkdir( $output_directory ) ) {
						throw new FilesystemException( "Unable to create output directory '{$output_directory}'." );
					}
				}
				
				if ( file_exists( $file_output_path ) ) {
					// Check if the unminified file has been modified since the minified one has
					// been created
					if ( Filesystem::getModifiedDateTime( $file_output_path ) > Filesystem::getModifiedDateTime( $file_path ) ) {
						continue;
					}
				}
				
				$minifier = new Minify\CSS( $file_path );
				$minifier->minify( $file_output_path );
			}
		}
		return $this;
	}
	
	public function execute ( ) {
		return $this->minifyScripts()->minifyStylesheets();
	}
}
