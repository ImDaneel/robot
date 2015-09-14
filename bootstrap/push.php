<?php

require __DIR__.'/autoload.php';

$app = require_once __DIR__.'/start.php';

$app->register(new Illuminate\Database\DatabaseServiceProvider($app));
$app->boot();

require __DIR__.'/../app/PushService/Monitor.php';

$id = '1234567890';
$msg = 'This is a push message.';
$ret = Monitor::push($id, $msg);
if ($ret) {
    echo "success\n";
} else {
    echo "failed\n";
}

