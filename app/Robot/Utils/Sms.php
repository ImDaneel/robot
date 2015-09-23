<?php namespace Robot\Utils;

use Config;

class Sms
{
    private static $errorMessage = [
        '112300' => '接收短信的手机号码为空',
        '112301' => '短信正文为空',
        '112302' => '群发短信已暂停',
        '112303' => '应用未开通短信功能',
        '112304' => '短信内容的编码转换有误',
        '112305' => '应用未上线，短信接收号码外呼受限',
        '112306' => '接收模板短信的手机号码为空',
        '112307' => '模板短信模板ID为空',
        '112308' => '模板短信模板data参数为空',
        '112309' => '模板短信内容的编码转换有误',
        '112310' => '应用未上线，模板短信接收号码外呼受限',
        '112311' => '短信模板不存在',
    ];

    public static function send($phone, $code, $interval)
    {
        $baseUrl = Config::get('app.rest_server', "https://sandboxapp.cloopen.com:8883");

        $sid = '8a48b5514fd49643014ff2c8913149e4';
        $authToken = '4c10e9750b9c44e9ac45c25021f392b9';
        $datetime = date('YmdHis', time());
        $sig = md5($sid.$authToken.$datetime);
        $sig = strtoupper($sig);

        $url = $baseUrl.'/2013-12-26/Accounts/'.$sid.'/SMS/TemplateSMS?sig='.$sig;

        $auth = base64_encode($sid.':'.$datetime);
        $option = [
            'header' => [
                'Accept:application/json',
                'Content-Type:appplication/json;charset=utf-8',
                'Authorization:'.$auth,
            ],
            'param' => json_encode([
                'to' => $phone,
                'appId' => '8a48b5514fd49643014ff2de0a7b4a66',
                'templateId' => '1',
                'datas' => [$code, $interval],
            ], true),
        ];

        $output = Curl::request($url, 'POST', $option);
        $outArray = json_decode($output, true);
        if (! isset($outArray['statusCode']) || $outArray['statusCode'] != '000000') {
            if (! isset($outArray['statusCode']) || ! isset(static::$errorMessage[$outArray['statusCode']])) {
                $error = '未知错误';
            } else {
                $error = static::$errorMessage[$outArray['statusCode']];
            }
            return ['code' => 'failed', 'error' => $error];
        }

        return ['code' => 'success'];
    }

}
