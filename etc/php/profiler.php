<?php

\XH::start();

class XH
{
    static function start()
    {
        if (!extension_loaded('tideways_xhprof')) {
            error_log('xhgui - either extension tideways must be loaded');
            return;
        }
        return;
        tideways_xhprof_enable(TIDEWAYS_XHPROF_FLAGS_CPU | TIDEWAYS_XHPROF_FLAGS_MEMORY);
        register_shutdown_function(
            function () {
                \XH::stop();
            }
        );
    }

    static function stop()
    {
        ignore_user_abort(true);
        flush();

        $data = [
            'profile' => tideways_xhprof_disable(),
            'meta' => [
                'server' => $_SERVER,
                'get' => $_GET,
                'env' => $_ENV,
            ]
        ];

        try {
            self::send('http://xhgui/api.php', $data);
        } catch (Exception $e) {
            error_log('xhgui - ' . $e->getMessage());
        }
    }

    private static function send($url, $data)
    {
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === false) {
            error_log(file_get_contents('php://input'));
            throw new Exception('fail to send data');
        }
    }
}
