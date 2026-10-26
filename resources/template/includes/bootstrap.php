<?php
use Wolfgang\Exceptions\Message\HTTP\ServiceUnavailable;
use Wolfgang\Exceptions\InvalidStateException;

if( file_exists(DB_LOCK_FILE)) {
	throw new ServiceUnavailable("Service is currently unavailable as updates are taking place. Please check back later." );
}

if ( ! file_exists( VENDOR_DIRECTORY ) ) {
	throw new \Exception( "Vendor directory does not exist. Please execute 'php composer.phar update' in the root directory." );
}

$required_extensions = [
	'gd',
	'date',
	'openssl',
	'zlib',
	'mysqlnd',
	'igbinary',
	'mysqli',
	'Phar',
	'curl',
	'dom',
	'mbstring',
	'pcre',
    'bcmath'
];

$loaded_extensions = get_loaded_extensions();

foreach($required_extensions as $extension){
	if(!in_array($extension, $loaded_extensions)){
		throw new InvalidStateException("The extension '{$extension}' is required but is not loaded.");
	}
}
