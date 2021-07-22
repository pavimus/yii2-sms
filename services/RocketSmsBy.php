<?php

namespace pavimus\sms\services;


class RocketSmsBy extends \pavimus\sms\Service {
    public $username;
    public $password;
    public $sender;

    private function callApi($method,$params=[]) {
        $params=array_merge($params,[
            'username'=>$this->username,
            'password'=>$this->password,
        ]);

        if ($this->sender) {
            $params['sender']=$this->sender;
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://api.rocketsms.by/json/'.$method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $response = curl_exec($curl);
        $result = @json_decode($response, true);

        if (!$result) {
            throw new \Exception('RocketSMS.by api error: '.$response);
        }

        if ($result && isset($result['error'])) {
            throw new \Exception("RocketSMS.by api error, ErrorID=" . $result['error']);
        }

        return $result;
    }

    protected function send_internal($phone, $text, $priority, &$smsId) {
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

    public function getAvailableSMS() {
        try {
            $result=$this->callApi('balance');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return intval($result['credits']);
    }
}