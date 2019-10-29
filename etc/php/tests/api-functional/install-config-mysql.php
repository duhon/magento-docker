<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'language'                     => 'en_US',
    'timezone'                     => 'America/Los_Angeles',
    'currency'                     => 'USD',
    'db-host'                      => 'db',
    'db-name'                      => 'magento',
    'db-user'                      => 'root',
    'db-password'                  => '',
    'db-prefix'                    => 'm2_',
    'backend-frontname'            => 'admin',
    'base-url'                     => 'http://magento.test/',
    'use-secure'                   => '0',
    'use-rewrites'                 => '0',
    'admin-lastname'               => 'Admin',
    'admin-firstname'              => 'Admin',
    'admin-email'                  => 'admin@example.com',
    'admin-user'                   => 'admin',
    'admin-password'               => '123123q',
    'admin-use-security-key'       => '0',
    /* PayPal has limitation for order number - 20 characters. 10 digits prefix + 8 digits number is good enough */
    'sales-order-increment-prefix' => time(),
    'session-save'                 => 'db',
    'cleanup-database'             => true,
    'amqp-host'                    => 'rabbit',
    'amqp-port'                    => '5672',
    'amqp-user'                    => 'guest',
    'amqp-password'                => 'guest',
];
