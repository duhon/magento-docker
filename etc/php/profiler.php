<?php

\XH::start();

class XH
{
    static function start()
    {
        if (empty($_REQUEST['xhprof_enabled']) && !getenv('xhprof_enabled')) {
            return;
        }
        if (!extension_loaded('tideways') && !extension_loaded('xhprof')) {
            error_log('xhgui - either extension tideways_xhprof or xhprof must be loaded');
            return;
        }
        try {
            tideways_enable(TIDEWAYS_FLAGS_CPU | TIDEWAYS_FLAGS_MEMORY);
        } catch (Throwable $t) {
            xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
        }
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
        try {
            $raw = tideways_disable();
        } catch (Throwable $t) {
            $raw = xhprof_disable();
        }
        //https://github.com/perftools/xhgui/issues/209
        $profile = [];
        foreach($raw as $key => $value) {
            $profile[strtr($key, ['.' => '_'])] = $value;
        }
        $data = [
            'profile' => $profile,
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
