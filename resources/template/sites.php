<?php

return [
    'site' => [
        'id' => 1,
        'name' => 'Site',
        'skin_domain' => [
            'domain' => '{{config.app.root_domain}}',
            'url' => 'https://{{config.app.root_domain}}',
            'api_domain' => 'api.{{config.app.root_domain}}',
            'api_url' => 'https://api.{{config.app.root_domain}}',
            'open' => true,
        ],
     
    ], //End site

    'cli' => [
        'id' => 2,
        'name' => 'CLI',
    ] //End site
];
