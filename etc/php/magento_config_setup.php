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

$filesConfig = include "/tmp/magento-config.php";
if (false === $filesConfig) {
    exec("cp /tmp/magento-config.php.dist /tmp/magento-config.php");
    $filesConfig = include "/tmp/magento-config.php";
}
applyConfig($filesConfig);
