<?php

namespace pavimus\sms\services;

use Yii;

/**
 * Class Debug
 * @package pavimus\sms\services
 */
class Debug extends \pavimus\sms\Service {

    public function send($phone, $text, $priority, &$smsId) {
        $smsId=microtime(true);

        Yii::error('sms',Yii::t("sms","send sms to phone '{phone}', id: {smsId} with text:\n{text}",[
            'phone'=>$phone,
            'smsId'=>$smsId,
            'text'=>$text
        ]));

        return true;
    }
}