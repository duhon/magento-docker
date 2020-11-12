<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'db-host' => 'mysql8',
    'db-user' => 'root',
    'db-password' => '',
    // use the same DB (call web-abi inside integration test)
//    'db-name' => 'magento',
    'db-name' => 'magento_integration_tests',
    'db-prefix' => '',
    'search-engine' => 'elasticsearch6',
    'elasticsearch-host' => 'elastic',
    'elasticsearch-port' => '9200', 
    'backend-frontname' => 'backend',
    'admin-user' => \Magento\TestFramework\Bootstrap::ADMIN_NAME,
    'admin-password' => \Magento\TestFramework\Bootstrap::ADMIN_PASSWORD,
    'admin-email' => \Magento\TestFramework\Bootstrap::ADMIN_EMAIL,
    'admin-firstname' => \Magento\TestFramework\Bootstrap::ADMIN_FIRSTNAME,
    'admin-lastname' => \Magento\TestFramework\Bootstrap::ADMIN_LASTNAME,
    'amqp-host' => 'rabbit',
    'amqp-port' => '5672',
    'amqp-user' => 'guest',
    'amqp-password' => 'guest',
    'document-root-is-pub'   => 'true',
    'consumers-wait-for-messages' => '0',
];
