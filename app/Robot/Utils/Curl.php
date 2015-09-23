<?php namespace Robot\Utils;

class Curl
{
    public static function request($url, $method = 'GET', array $option = array())
    {
        //初始化
        $ch = curl_init();
        
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        // header
        if (isset($option['header'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $option['header']);
        }
        
        // post数据
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            $post_data = isset($option['param']) ? $option['param'] : array();
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        
        // cookie
        if (isset($option['cookie'])) {
            $cookieString = '';
            if (isset($option['cookie']['sessionID']) 
                && $option['cookie']['sessionID'] != null) {
                $cookieString .= ('sessionID=' . $option['cookie']['sessionID']);
            }
            
            curl_setopt($ch, CURLOPT_COOKIE, $cookieString);
        }
        
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        
        //释放curl句柄
        curl_close($ch);

        return $output;
    }
}
