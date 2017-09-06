<?php

namespace pavimus\sms;


abstract class Service extends \yii\base\Object {
    /**
     * @param $phone phone number in international format, only digits, for example 375295676678
     * @param $text message text
     * @param $priority - if true, send message with high priority
     * @param $smsId return smsId, returned by service
     * @return $mixed result: true, if sms succesfully sent or error message
     */
    public abstract function send($phone, $text, $priority, &$smsId);

    /**
     * Get Available sms count on service balance
     * @return int - amount of available to send sms, string in case of error
     */
    public abstract function getAvailableSMS();
}