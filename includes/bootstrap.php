<?php
if ( ! file_exists( WOLFGANG_VENDOR_DIRECTORY ) ) {
	throw new \Exception( "Wolfgang vendor directory does not exist. Please execute 'php composer.phar update' in the root directory." );
}