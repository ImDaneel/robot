<?php

require '/home/vagrant/robot/app/models/Client.php';

class PushServer
{

    public static function listen($listenAddr)
    {
        $socket = stream_socket_server($listenAddr, $errno, $errstr, STREAM_SERVER_BIND);
        if ($socket == null) {
            die("Create socket error: $errstr ($errno)");
        }

        while (true) {
            $msg = stream_socket_recvfrom($socket, 1024, 0, $peer);
            
            $msgArray = json_decode($msg, true);
            if (! isset($msgArray['topic']) || $msgArray['topic'] != 'ShakeHands' 
                || ! isset($msgArray['content'])) {
                continue;
            }

            //echo "Client : $peer\n";
            //echo "Receive : {$msg}\n";
/*            $retArray = array(
                'code' => 'success',
                'message' => "receive from $peer",
            );
            $outMsg = json_encode($retArray, true);
            stream_socket_sendto($socket, $outMsg, 0, $peer);
*/
            static::checkin($peer, $msgArray['content']);
        }
    }

    private static function checkin($addr, $data)
    {
        $data['external_addr'] = $addr;
        $data['updated_at'] = time();
        $where = array('sign'=>$data['sign']);

        try {
            $client = Client::updateOrCreate($where, $data);
        } catch (\Exception $e) {
            // do noting
        }
    }

    public static function push($type, $sign, array $content)
    {
        $client = Client::find($sign);
        if ($client == null) {
            return false;
        }

        $clientAddr = 'udp://' . $client['external_addr'];
        $handle = stream_socket_client($clientAddr, $errno, $errstr);
        if ($handle == null) {
            return false;
        }

        $message = json_encode([
            'topic' => 'Push'.ucfirst(strtolower($type)),
            'content' => $content,
        ], true);
        if ($message == null) {
            return false;
        }

        $success = false;
        stream_set_timeout($handle, 3);
        for ($i = 0; $i < 3; $i++) {
            fwrite($handle, $message);
            $result = fread($handle, 1024);

            $retArray = json_decode($result, true);
            if (isset($retArray['topic']) && $retArray['topic'] == 'PushAck') {
                $success = true;
                break;
            }
        }

        fclose($handle);
        return $success;
    }

}

