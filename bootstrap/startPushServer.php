<?php

require __DIR__.'/autoload.php';

$app = require_once __DIR__.'/start.php';

$app->register(new Illuminate\Database\DatabaseServiceProvider($app));
$app->boot();

require __DIR__.'/../app/Robot/Utils/PushServer.php';

$listenAddr = getenv('listen_addr');
PushServer::listen($listenAddr);

