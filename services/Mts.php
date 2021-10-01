<?php

namespace pavimus\sms\services;


class Mts extends \pavimus\sms\Service {
    public $username;
    public $password;
    public $sender;
    public $clientId;
    public $ttl = 86400;

    private function callApi($method,$params=[]) {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://api.communicator.mts.by/' . $this->clientId . '/json2/' . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);

        $response = curl_exec($curl);
        $result = @json_decode($response, true);

        if (!$result) {
            throw new \Exception('Mts api error: '.$response);
        }

        if ($result && isset($result['error_text'])) {
            throw new \Exception("Mts api error: " . $result['error_text']);
        }

        return $result;
    }

    protected function send_internal($phone, $text, $priority, &$smsId) {
        $params=[
            "phone_number" => $phone,
            "channels" => [
               "sms"
            ],
            "channel_options" => [
               "sms" => [
                  "text" => $text,
                  "alpha_name" => $this->sender,
                  "ttl" => $this->ttl
               ]
            ]
        ];

        try {
            $result = $this->callApi('simple', $params);
            $smsId = $result['message_id'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function getAvailableSMS() {
        return 999999;
    }
}