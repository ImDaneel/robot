<?php

require __DIR__.'/autoload.php';

$app = require_once __DIR__.'/start.php';

$app->register(new Illuminate\Database\DatabaseServiceProvider($app));
$app->boot();

require __DIR__.'/../app/PushService/Monitor.php';

$listenAddr = getenv('listern_addr');
Monitor::listen($listenAddr);

