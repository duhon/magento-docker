<?php

function applyConfig($config, $parentNode = '') {

    if (is_array($config)) {
        foreach ($config as $subNode => $value) {
            if (is_array($value)) {
                applyConfig($value, ($parentNode ? $parentNode . '/' : "") . $subNode) ;
            } else {
                $path = $parentNode . '/' . $subNode;
                setMagentoConfig($path, $value);
            }
        };
    }
}

function setMagentoConfig($path, $value) {

    if ((false === strpos($path, 'sandbox_private_key') && !empty(trim($value))) ||
        (false !== strpos($path, 'sandbox_private_key') && strlen($value) > 128)
    ) {
        exec( 'bin/magento config:set -- ' . $path . ' "' . $value . '"');
    }
}

$magentoConfig = include "/tmp/magento-config.php";
applyConfig($magentoConfig);
