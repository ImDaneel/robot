<?php namespace Robot\Utils;

use Client;

class PushService
{
    public static function push($type, $sign, array $content)
    {
        $client = Client::find($sign);
        if ($client == null) {
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

        $gmClient = new \GearmanClient();
        $gmClient->addServer();

        $ret = $gmClient->doNormal('Push', $message);
        if ($ret == 'success') {
            return true;
        }
        return false;
    }
}

