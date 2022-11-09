<?php

return [
    /* filename */
    'data-services/DataServices/view/frontend/requirejs-config.js' => [
        /* replace from => to */
        'commerce.adobedtm.com/v6' => 'js.magento-datasolutions.com/qa/snowplow/events/v6',
        'magento-storefront-events-sdk@^1/dist/index' => 'magento-storefront-events-sdk@qa/dist/index',
        'magento-storefront-event-collector@^1/dist/index' => 'magento-storefront-event-collector@qa/dist/index',
    ],
    'services-id/ServicesId/etc/csp_whitelist.xml' => [
        'api.magento.com' => 'qa-api.magedevteam.com',
        'commerce.adobe.io' => 'commerce-int.adobe.io',
    ],
    'data-services/DataServices/etc/csp_whitelist.xml' => [
        'commerce.adobedtm.com' => 'js.magento-datasolutions.com',
        'commerce.adobedc.net' => 'com-magento-qa1.collector.snplow.net',
    ],
    'magento-live-search/LiveSearchStorefrontPopover/etc/config.xml' => [
        'livesearch-autocomplete.magento-ds.com' => 'searchautocompleteqa.magento-datasolutions.com'
    ],
    'services-id/ServicesId/etc/config.xml' => [
        // temporary, until v1 released
        'services-connector-ui.magento-ds.com/v1/index.js' => 'services-connector-qa.magento-datasolutions.com/v1/index.js'
    ]
];
