<?php
set_include_path( get_include_path() . PATH_SEPARATOR . WOLFGANG_DIRECTORY . "vendor/" );
spl_autoload_extensions( '.class.php,.interface.php,.trait.php' );

// SPL Autoload for Non-Test Classes/Traits/Interfaces
spl_autoload_register( function ( $class ) {
	if ( preg_match( "/(Wolfgang)/", $class ) ) {
		
		$class = str_replace( "\\", "/", $class );
		$path = WOLFGANG_CLASSES_DIR;
		$class_parts = explode( "/", $class );
		
		array_shift( $class_parts );
		
		foreach ( $class_parts as $key => $part ) {
			if ( $key != 0 ) {
				$path .= '/';
			}
			$path .= $part;
		}
		
		$file = $path . '.class.php';
		if ( ! file_exists( $file ) ) {
			$file = $path . '.interface.php';
			if ( ! file_exists( $file ) ) {
				$file = $path . '.trait.php';
				if ( ! file_exists( $file ) ) {
					return false;
				}
			}
		}
		include_once ($file);
	}
} );

// SPL Autoload for Test Classes/Traits/Interfaces
spl_autoload_register( function ( $class ) {
	$class = str_replace( "\\", "/", $class );
	$path = WOLFGANG_TESTS_CLASSES_DIRECTORY;
	$class_parts = explode( "/", $class );
	
	array_shift( $class_parts );
	array_shift( $class_parts );
	
	foreach ( $class_parts as $key => $part ) {
		if ( $key != 0 ) {
			$path .= '/';
		}
		$path .= $part;
	}
	
	$file = $path . '.class.php';
	
	if ( file_exists( $file ) ) {
		include_once ($file);
	}
	
	return false;
} );