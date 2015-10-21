<?php

class ClientOberser
{
    public function saved($model)
    {
        $notifications = PushNotification::where(['sign'=>$model->sign])->get()->toArray();
        if (empty($notifications)) {
            return;
        }

        $message = json_encode([
            'batch' => true,
            'address' => $model->external_addr,
            'notifications' => $notifications,
        ], true);
        if ($message == null) {
            return;
        }

        $gmClient = new GearmanClient();
        $gmClient->addServer();
        $gmClient->doNormal('Push', $message);
    }
}
