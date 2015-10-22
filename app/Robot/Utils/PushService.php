<?php namespace Robot\Utils;

use Client;
use PushNotification;

class PushService
{
    public static function push($type, $sign, array $content,  $immediate = true)
    {
        try {
            $notification = static::saveNotification($type, $sign, $content);
            $client = Client::where(['sign'=>$sign])->firstOrFail();
        } catch (\Exception $e) {
            return false;
        }

        if (! $immediate) {
            return true;
        }

        $messageArray = $notification->toArray();
        $messageArray['address'] = $client['external_addr'];
        $message = json_encode($messageArray, true);
        if ($message == null) {
            echo "json error\n";
            return false;
        }

        $gmClient = new \GearmanClient();
        $gmClient->addServer();

        $ret = $gmClient->doNormal('Push', $message);
        if ($ret != 'success') {
            return false;
        }
        return true;
    }

    private static function saveNotification($type, $sign, array $content)
    {
        $contentStr = json_encode($content, true);
        $msgId = md5($type . $sign . $contentStr . date('YmdHis', time()) . rand(1000, 9999));
        $topic = 'Push' . ucfirst(strtolower($type));

        return PushNotification::create([
            'msg_id' => $msgId,
            'sign' => $sign,
            'topic' => $topic,
            'content' => $contentStr,
            'created_at' => time()
        ]);
    }
}
