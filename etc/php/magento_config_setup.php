<?php

use Magento\Framework\App\Bootstrap;

const CONFIG_REQUIRING_ENCRYPTION = [
    'services_connector/services_connector_integration/sandbox_api_key',
    'services_connector/services_connector_integration/sandbox_private_key',
    'services_connector/services_connector_integration/production_api_key',
    'services_connector/services_connector_integration/production_private_key',
];

try {
    require '/var/www/magento2ce/app/bootstrap.php';

    $bootstrap = Bootstrap::create(BP, $_SERVER);
    $objectManager = $bootstrap->getObjectManager();
    /** @var \Magento\Framework\App\Config\Storage\WriterInterface $configWriter */
    $configWriter = $objectManager->get(\Magento\Framework\App\Config\Storage\WriterInterface::class);
    /** @var \Magento\Framework\Encryption\Encryptor $encryptor */
    $encryptor = $objectManager->get(\Magento\Framework\Encryption\Encryptor::class);
} catch (\Exception $e) {
    echo 'Autoload error: ' . $e->getMessage();
    exit(1);
}

function applyConfig(
    array $config,
    \Magento\Framework\App\Config\Storage\WriterInterface $writer,
    \Magento\Framework\Encryption\Encryptor $encryptor,
    $parentNode = ''
) {

    if (is_array($config)) {
        foreach ($config as $subNode => $value) {
            if (is_array($value)) {
                applyConfig($value, $writer, $encryptor, ($parentNode ? $parentNode . '/' : "") . $subNode);
            } else {
                $path = $parentNode . '/' . $subNode;
                setMagentoConfig($path, $writer, $encryptor, $value);
            }
        };
    }
}

function setMagentoConfig(
    $path,
    \Magento\Framework\App\Config\Storage\WriterInterface $writer,
    \Magento\Framework\Encryption\Encryptor $encryptor,
    $value
) {
    if ((false === strpos($path, 'sandbox_private_key') && !empty(trim($value))) ||
        (false !== strpos($path, 'sandbox_private_key') && strlen($value) > 128)
    ) {
        if (in_array($path, CONFIG_REQUIRING_ENCRYPTION)) {
            $value = $encryptor->encrypt($value);
        }

        $writer->save($path, $value);
    }
}

$magentoConfig = include "/tmp/magento-config.php";
applyConfig($magentoConfig, $configWriter, $encryptor);
