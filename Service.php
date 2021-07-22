<?php

namespace pavimus\sms;

use Yii;
use \pavimus\sms\models\Sms;

abstract class Service extends \yii\base\Object {
    public $gateway;

    /**
     * @param $phone phone number in international format, only digits, for example 375295676678
     * @param $text message text
     * @param $priority - if true, send message with high priority
     * @param $smsId return smsId, returned by service
     * @param $smsBatchId smsBatchId, if sms was sent from batch
     * @return $mixed result: true, if sms succesfully sent or error message
     */
    public function send($phone, $text, $priority, &$smsId, $smsBatchId = null) {
        $res=$this->send_internal($phone, $text, $priority, $smsId);

        try {
            $sms=new Sms();
            $sms->sms_batch_id=$smsBatchId;
            $sms->service_sms_id=strval($smsId);
            $sms->dt=new \yii\db\Expression('now()');
            $sms->phone=$phone;
            $sms->text=$text;
            $sms->parts=$this->gateway->calculateSMSCount($text);

            if ($res!==true) {
                $sms->error=$res;
            }

            $sms->save();
        } catch(\Exception $e) {
            Yii::error($e->getMessage());
        }

        return $res;
    }

    protected abstract function send_internal($phone, $text, $priority, &$smsId);

    /**
     * Get Available sms count on service balance
     * @return int - amount of available to send sms, string in case of error
     */
    public abstract function getAvailableSMS();
}
