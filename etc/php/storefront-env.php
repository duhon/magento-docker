<?php
return [
    'catalog-store-front' => [
        'connections' => [
            'default' => [
                'protocol' => 'http',
                'hostname' => 'elastic',
                'port' => '9200',
                'username' => '',
                'password' => '',
                'timeout' => 3
            ]
        ],
        'timeout' => 60,
        'alias_name' => 'catalog_storefront',
        'source_prefix' => 'catalog_storefront_v',
        'source_current_version' => 1
    ],
    'queue' => [
        'consumers_wait_for_messages' => 0
    ],
];