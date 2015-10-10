<?php

require __DIR__.'/../bootstrap/autoload.php';

$app = require_once __DIR__.'/../bootstrap/start.php';
$app->register(new Illuminate\Database\DatabaseServiceProvider($app));
$app->boot();

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction('ShakeHands', function(GearmanJob $job) {
    $data = json_decode($job->workload(), true);
    if ($data == null)  {
        return false;
    }

    $data['updated_at'] = time();
    $where = array('sign'=>$data['sign']);

    try {
        $client = Client::updateOrCreate($where, $data);
    } catch (\Exception $e) {
        // do noting
        return false;
    }

    return true;
});

while ($worker->work()) {
    if ($worker->returnCode() != GEARMAN_SUCCESS) {
        echo "return_code: " . $worker->returnCode() . "\n";
    }
}
