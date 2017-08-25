<?php

namespace pavimus\sms\services;


class RocketSmsBy extends \pavimus\sms\Service {
    public $username;
    public $password;

    public function send($phone, $text, $priority, &$smsId) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://api.rocketsms.by/json/send');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $params=[
            'username'=>$this->username,
            'password'=>$this->password,
            'phone'=>$phone,
            'text'=>$text
        ];

        if ($priority) {
            $params['priority']='true';
        }

        $encodedParams=[];
        foreach($params as $k=>$v) {
            $encodedParams[]="$k=".rawurlencode($v);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&',$encodedParams));

        $result = @json_decode(curl_exec($curl), true);

        if ($result && isset($result['id'])) {
            $smsId=$result['id'];
            return true;
        } elseif ($result && isset($result['error'])) {
            return "Error occurred while sending message. ErrorID=" . $result['error'];
        } else {
            return "Service error";
        }
    }
}