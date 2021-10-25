<?php
return [
    'system' => [
        'default' => [
            'magento_saas' => [
                'environment' => 'sandbox',
            ],
            'live_search' => [
                'frontend_url' => 'https://search-admin-ui-qa.magento-datasolutions.com/v0/admin.js',
            ],
            'live_search_metrics' => [
                'metrics_url' => 'https://livesearch-metrics-qa.magento-datasolutions.com/v0/liveSearchMetrics.js',
            ],
            'live_search_storefront_popover' => [
                'frontend_url' => 'https://searchautocompleteqa.magento-datasolutions.com/v0/LiveSearchAutocomplete.js',
            ],
            'services_connector' => [
                'sandbox_gateway_url' => 'https://commerce-int.adobe.io/',
            ],
        ],
    ],
];