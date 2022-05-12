<?php

use Magento\Framework\App\Bootstrap;

try {
    require '/var/www/magento2ce/app/bootstrap.php';

    $bootstrap = Bootstrap::create(BP, $_SERVER);
    $objectManager = $bootstrap->getObjectManager();
    /** @var \Magento\Framework\App\Config\Storage\WriterInterface $configWriter */
    $configWriter = $objectManager->get(\Magento\Framework\App\Config\Storage\WriterInterface::class);
} catch (\Exception $e) {
    echo 'Autoload error: ' . $e->getMessage();
    exit(1);
}

function applyConfig($config, $writer, $parentNode = '') {

    if (is_array($config)) {
        foreach ($config as $subNode => $value) {
            if (is_array($value)) {
                applyConfig($value, $writer, ($parentNode ? $parentNode . '/' : "") . $subNode) ;
            } else {
                $path = $parentNode . '/' . $subNode;
                setMagentoConfig($path, $writer, $value);
            }
        };
    }
}

function setMagentoConfig($path, $writer, $value) {

    if ((false === strpos($path, 'sandbox_private_key') && !empty(trim($value))) ||
        (false !== strpos($path, 'sandbox_private_key') && strlen($value) > 128)
    ) {
        $writer->save($path, $value);
    }
}

$magentoConfig = include "/tmp/magento-config.php";
applyConfig($magentoConfig, $configWriter);
