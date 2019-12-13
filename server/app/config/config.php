<?php
return [
    'settings' => [
        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    'app/src/Entity'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' => __DIR__.'/../cache/proxies',
                'cache' => null,
                'simple_annotation_reader' => false,
            ],
            'connection' => [
                'driver'   => 'pdo_mysql',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'dbname'   => 'mem',
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
            ],
        ],
        'aws' => [
            's3' => [
                'meta' => [
                    'bucket' => 'mem-buckoo',
                ],
                'connection' => [
                    'region'      => 'us-east-1',
                    'version'     => 'latest',
                    'credentials' => [
                        'key'    => getenv('AWS_KEY'),
                        'secret' => getenv('AWS_SECRET'),
                    ],
                ],
            ],
        ],
        'db' => [
            'secret' => getenv('DB_SECRET'),
        ],
    ],
];
