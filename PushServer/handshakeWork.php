<?php

require __DIR__.'/../bootstrap/autoload.php';

$app = require_once __DIR__.'/../bootstrap/start.php';
$app->register(new Illuminate\Database\DatabaseServiceProvider($app));
$app->boot();

require_once 'ClientOberser.php';
Client::observe(new ClientOberser());

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction('ShakeHands', function(GearmanJob $job) {
    $data = json_decode($job->workload(), true);
    if ($data == null)  {
        return false;
    }

    try {
        $values = $data['content'];
        $values['external_addr'] = $data['address'];
        $values['updated_at'] = time();

        $client = Client::updateOrCreate(['sign'=>$values['sign']], $values);
    } catch (\Exception $e) {
        return false;
    }
    return true;
});

$worker->addFunction('PushAck', function(GearmanJob $job) {
    $data = json_decode($job->workload(), true);
    if ($data == null) {
        return false;
    }

    try {
        PushNotification::where(['msg_id'=>$data['msg_id']])->firstOrFail()->delete();
    } catch (\Exception $e) {
        return false;
    }
    return true;
});

while ($worker->work()) {
    if ($worker->returnCode() != GEARMAN_SUCCESS) {
        echo "return_code: " . $worker->returnCode() . "\n";
    }
}
