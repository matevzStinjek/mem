<?php
return [
    'settings' => [
        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    'app/src/Entity'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' =>  __DIR__.'/../cache/proxies',
                'cache' => null,
            ],
            'connection' => [
                'driver'   => 'pdo_mysql',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'dbname'   => 'mem',
                'user'     => 'root',
                'password' => 'die2wice',
            ],
        ],
        'aws' => [
            's3' => [
                'region'      => 'us-east-2',
                'version'     => 'latest',
                'credentials' => [
                    'key'    => '',
                    'secret' => '',
                ],
            ],
        ],
    ],
];
