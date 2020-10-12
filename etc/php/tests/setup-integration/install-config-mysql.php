<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

return [
    'default' => [
        'db-host' => 'db',
        'db-user' => 'root',
        'db-password' => '',
        'db-name' => 'magento_integration_tests',
        'db-prefix' => '',
        'backend-frontname' => 'admin',
        'admin-user' => 'admin',
        'admin-password' => '123123q',
        'admin-email' => \Magento\TestFramework\Bootstrap::ADMIN_EMAIL,
        'admin-firstname' => \Magento\TestFramework\Bootstrap::ADMIN_FIRSTNAME,
        'admin-lastname' => \Magento\TestFramework\Bootstrap::ADMIN_LASTNAME,
        'enable-modules' => 'Magento_TestSetupModule2,Magento_TestSetupModule1,Magento_Backend',
        'disable-modules' => 'all'
    ],
    'checkout' => [
        'host' => 'db',
        'username' => 'root',
        'password' => '',
        'dbname' => 'magento_integration_tests'
    ],
    'sales' => [
        'host' => 'db',
        'username' => 'root',
        'password' => '',
        'dbname' => 'magento_integration_tests'
    ]
];
