<?php namespace Robot\Utils;

use Client;
use PushNotifications;

class PushService
{
    public static function push($type, $sign, array $content)
    {
        $client = Client::find($sign);
        if ($client == null) {
            static::save($type, $sign, $content);
            return false;
        }

        $message = json_encode([
            'type' => $type,
            'address' => $client['external_addr'],
            'content' => $content,
        ], true);
        if ($message == null) {
            return false;
        }

        $gmClient = new GearmanClient();
        $gmClient->addServer();

        $ret = $gmClient->doNormal('Push', $message);
        if ($ret != 'success') {
            static::save($type, $sign, $content);
            return false;
        }
        return true;
    }

    private static function save($type, $sign, array $content)
    {
        $message = json_encode([
            'topic' => 'Push' . ucfirst(strtolower($type)),
            'content' => $content,
        ], true);
        if ($message == null) {
            return;
        }

        PushNotifications::create([
            'sign' => $sign,
            'message' => $message,
            'created_at' => time()
        ]);
    }
}

