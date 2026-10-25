<?php
set_include_path(get_include_path() . PATH_SEPARATOR . CLASSES_DIRECTORY);
set_include_path(get_include_path() . PATH_SEPARATOR . DOCUMENT_ROOT . "vendor/");

spl_autoload_extensions('.php,.class.php,.interface.php,.trait.php');

$composer_classloader = include (DOCUMENT_ROOT . "vendor/autoload.php");

$composer_classmap = $composer_classloader->getClassMap();

// new classmap
$class_map = array();

foreach ( $composer_classmap as $class => $class_path ) {
	$class_map[strtolower($class)] = $class_path;
}

spl_autoload_register(function ($class) use ($class_map) {
	
	$class = strtolower($class);
	
	if(!isset($class_map[$class])) {
		return false;
	}
	
	include_once ($class_map[$class]);
});
