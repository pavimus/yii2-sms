<?php

namespace pavimus\sms\services;


class RocketSmsBy extends \pavimus\sms\Service {
    public $username;
    public $password;

    private function callApi($method,$params) {
        $params=array_merge($params,[
            'username'=>$this->username,
            'password'=>$this->password,
        ]);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://api.rocketsms.by/json/'.$method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $result = @json_decode(curl_exec($curl), true);

        if (!$result) {
            throw new \Exception('RocketSMS.by api error');
        }

        if ($result && isset($result['error'])) {
            throw new \Exception("RocketSMS.by api error, ErrorID=" . $result['error']);
        }

        return $result;
    }

    public function send($phone, $text, $priority, &$smsId) {
        $params=[
            'phone'=>$phone,
            'text'=>$text
        ];

        if ($priority) {
            $params['priority']='true';
        }

        try {
            $result=$this->callApi('send',$params);
            $smsId=$result['id'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }
}