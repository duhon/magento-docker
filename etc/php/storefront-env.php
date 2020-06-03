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
    'system' => [
        'default' => [
            'services_connector' => [
                'sandbox_gateway_url' => 'https://qa-api.magedevteam.com/',
                'production_gateway_url' => 'https://int-api.magedevteam.com/',
                'api_portal_url' => 'https://account-stage.magedevteam.com/apiportal/index/index/'
            ]
        ]
    ]
];