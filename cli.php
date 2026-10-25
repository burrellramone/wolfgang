#!/usr/bin/env php
<?php

// Example usage: ./cli.php --create-project --name myapp --overwrite --dest /var/www/myapp/ --root-domain myapp.com

require __DIR__ . '/includes/common.php';

class Cli {
    public function createProject(array $options): void {
        $project_name = strtolower($options['name'] ?? $options['n'] ?? '');

        // Root domain for the project
        $root_domain = $options['root-domain'] ?? $options['r'] ?? null;

        // Destination of project to create
        $destination = $options['dest'] ?? $options['d'] ?? null;

        // Overwrite project
        $overwrite = (isset($options['overwrite']) || isset($options['o'])) ? true : false;

        if (empty($project_name)) {
            throw new Exception("Project name not provided");
        } else if (empty($destination)) {
            throw new Exception("Project destination not provided");
        } else if (!is_dir($destination)) {
            throw new Exception("Destination '$destination' does not exist or is not a directory");
        } else if (empty($root_domain)) {
            throw new Exception("Root domain not provided");
        }

        $unzip_options = "";

        if ($overwrite) {
            $unzip_options .= "-o ";
        }

        shell_exec("unzip {$unzip_options}/var/www/wolfgang/resources/template.zip -d $destination");

        if ($overwrite) {
            shell_exec("rm -rf $destination/src/main/php/com/$project_name");
        }

        rename("$destination/src/main/php/com/__project__", "$destination/src/main/php/com/$project_name");


        $wolfgang_dir = "$destination/src/main/php/com/$project_name/Wolfgang";

        if (!file_exists($wolfgang_dir)) {
            mkdir($wolfgang_dir);
        }

        shell_exec("sed -i -e 's/__project__/{$project_name}/g' $destination/composer.json");
        shell_exec("sed -i -e 's/__project__/{$project_name}/g' $destination/includes/constants.php");
        shell_exec("sed -i -e 's/__root_domain__/{$root_domain}/g' $destination/config/default/app.ini");
        shell_exec("sed -i -e 's/__project__/{$project_name}/g' $destination/config/default/database.ini");

        // Copy Wolfgang
        shell_exec("rsync -av --exclude '.cache' --exclude '*.git' --exclude '*.gitmodules' --exclude '*.project' --exclude '*.buildpath' --exclude '*.phplint.cache' --exclude 'composer.lock' /var/www/wolfgang/ $destination/src/main/php/com/$project_name/Wolfgang/");
        
        shell_exec("cd $destination/ && composer install");
        shell_exec("cd $destination/src/main/php/com/$project_name/Wolfgang/ && composer install");
    }
}

$commands = [
    "help" => [],
    "create-project" => [
        [
            "n:",
            "r:",
            "d:",
            "o"
        ],
        [
            "name:",
            "root-domain:",
            "dest:",
            "overwrite"
        ]
    ]
];

$shortopts = [];
$longopts = [];

foreach ($commands as $command => $options) {
    $longopts[] = "$command";

    if (empty($options)) {
        continue;
    }

    $longopts = array_merge($longopts, $options[1]); 
    $shortopts = array_merge($shortopts, $options[0]);
}

$shortopts = implode("", $shortopts);
$cli_options = getopt($shortopts, $longopts);
$cli = new Cli();

try {
    foreach ($commands as $command_name => $options) {

        if (array_key_exists($command_name, $cli_options)) {
                switch ($command_name) {
                    case 'help':
                        echo file_get_contents("resources/HELP.txt") . PHP_EOL;
                        break;

                    case 'create-project':
                        $cli->createProject($cli_options);
                        break;
                }
        }
    }
} catch (Exception $e) {
    die($e->getMessage() . PHP_EOL);
}
