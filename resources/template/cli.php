#!/usr/bin/php
<?php
require_once ('includes/common.php');

//Wolfgang
use Wolfgang\Message\CLI\Request as CliRequest;
use Wolfgang\Util\CliWriter;
use Controller\Admin\Cli\UserTypes;

//AirportRuns
use Application\Cli;

#Excample useage:
#./cli.php -A Admin -c Users -a list

try {
    $application = Cli::getInstance();
    $response = $application->execute( CliRequest::getInstance() );

    CliWriter::info($response);
} catch(Exception $e) {
    echo CliWriter::error($e->getMessage());
}