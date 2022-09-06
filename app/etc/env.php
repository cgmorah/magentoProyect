<?php
return [
    'backend' => [
        'frontName' => 'xxxxxxxxxxxxx'
    ],
    'crypt' => [
        'key' => '35c35ebe5d3c248576d1db3ffa458a33'
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'localhost',
                'dbname' => 'xxxxxxxxxxxxx',
                'username' => 'xxxxxxxxxxxxx',
                'password' => 'xxxxxxxxxxxxx',
                'model' => 'xxxxxxxxxxxxx',
                'engine' => 'xxxxxxxxxxxxx',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1'
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'xxxxxxxxxxxxx',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'files'
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'id_prefix' => 'xxxxxxxxxxxxx'
            ],
            'page_cache' => [
                'id_prefix' => 'xxxxxxxxxxxxx'
            ]
        ]
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => null
        ]
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'google_product' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'vertex' => 1
    ],
    'downloadable_domains' => [
        'xxxxxxxxxxxxx'
    ],
    'install' => [
        'date' => 'Sat, 30 May 2020 09:38:49 +0000'
    ]
];
