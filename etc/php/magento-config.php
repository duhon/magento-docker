<?php

return [
    // admin settings
    'web' => [
        'seo' => [
            'use_rewrites' => 1,
        ]
    ],
    'dev' => [
        'template' => [
            'allow_symlink' => 1
        ]
    ],
    'admin' => [
        'security' => [
            'admin_account_sharing' => 1,
            'session_lifetime' => 31536000,
        ]
    ],
    // service connector (SaaS config)
    'services_connector' => [
        // get QA API credentials  https://account-stage.magedevteam.com/apiportal/index/index/
        'services_connector_integration' => [
            'sandbox_api_key' => '',
            'sandbox_private_key' =>
                <<< KEY
-----BEGIN PRIVATE KEY-----
replace with private key
-----END PRIVATE KEY-----
KEY
        ],
        // services_id node can be filled after initial install and keys setup
        // or if you already have installed Magento with LiveSearch
        'services_id' => [
            'project_name' => '',// "SaaS Project" in Admin
            'project_id' => '',// "SaaS Project" -> "Project ID:" in Admin
            'environment' => '',// "SaaS Data Space" in Admin
            'environment_id' => '',// "SaaS Data Space" -> "Data Space ID" in Admin
        ]
    ],
];
