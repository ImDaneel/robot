<?php

require __DIR__.'/../models/Client.php';

class Monitor
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
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $where = array('id'=>$data['id']);

        try {
            $client = Client::updateOrCreate($where, $data);
        } catch (\Exception $e) {
            // do noting
        }
    }

    public static function push($id, $message)
    {
        $client = Client::find($id);
        if ($client == null) {
            return false;
        }

        $timestamp = strtotime($client['timestamp']);
        if (time() - $timestamp > 3 * 60) {
            return false;
        }

        $clientAddr = 'udp://' . $client['external_addr'];
        $handle = stream_socket_client($clientAddr, $errno, $errstr);
        if ($handle == null) {
            return false;
        }

        $success = false;
        stream_set_timeout($handle, 3);
        for ($i = 0; $i < 3; $i++, sleep(2)) {
            fwrite($handle, $message);
            //stream_socket_sendto($handle, $message);
            $result = fread($handle, 1024);
            //$result = stream_socket_recvfrom($handle, 1024, 0, $peer);

            $retArray = json_decode($result, true);
            if ($retArray == null) {
                continue;
            }

            if ($retArray['code'] == 'success') {
                $success = true;
                break;
            }
        }

        fclose($handle);
        return $success;
    }

}

