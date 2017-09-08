<?php

namespace pavimus\sms;


/**
 * Class Sms
 * @package pavimus\sms
 */
class Gateway extends \yii\base\Object
{
    /**
     * Sms service configuration
     *
     * @var array
     */
    public $service;

    /**
     * Sms scheduler configuration
     *
     * @var array
     */
    public $scheduler;

    /**
     * Initialize the component.
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        if (!$this->service) {
            throw new \yii\base\ErrorException(\Yii::t('sms', 'Services are not configured.'));
        }

    }

    public function getService() {
        static $service;

        if (!$service) {
            $service=\Yii::createObject($this->service);
        }

        return $service;
    }

    public function getScheduler() {
        static $scheduler;

        if (!$scheduler) {
            $scheduler=\Yii::createObject($this->scheduler);
            $scheduler->gateway=$this;
        }

        return $scheduler;
    }

    /**
     * Include translation messages for component
     */
    public static function registerTranslations()
    {
        if (!isset(\Yii::$app->i18n->translations['sms']) && !isset(\Yii::$app->i18n->translations['sms/*'])) {
            \Yii::$app->i18n->translations['sms'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@vendor/pavimus/yii2-sms/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'sms' => 'sms.php'
                ]
            ];
        }
    }

    /**
     * Send sms
     * @param $phone - phone number (international format,only digits, for example 375296001010)
     * @param $text - message text
     * @param bool $priority - high priority or not.
     * @param null $smsId - returned by service sms id
     * @return mixed true if sms sent successfully, string with message in case of error
     */
    public function send($phone, $text, $priority=false, &$smsId=null) {
        $result=$this->getService()->send($phone, $text, $priority, $smsId);

        return $result;
    }

    /**
     * Get Available sms count on service balance
     * @return int - amount of available to send sms, string in case of error
     */
    public function getAvailableSMS() {
        $result=$this->getService()->getAvailableSMS();

        return $result;
    }

    /**
     * Return amount of sms, used for sending text
     * @param $text text in utf8
     * @return int - amount of sms
     */
    public function calculateSMSCount($text) {
        $mblen=mb_strlen($text);
        $len=strlen($text);

        if ($mblen != $len) {
            if ($mblen > 70) {
                return ceil($mblen/67-1e-6);
            } else {
                return 1;
            }
        } else {
            if ($mblen > 160) {
                return ceil($mblen/153-1e-6);
            } else {
                return 1;
            }
        }
    }

    /**
     * Send sms in batch
     * @param $destinations
     * @param $text text
     * @param $jobId returned job id
     * @return mixed
     */
    public function sendBatch($destinations, $text, &$jobId=null) {
        $result=$this->getScheduler()->sendBatch($destinations, $text, $jobId);

        return $result;
    }

    /**
     * Send sms from queue
     */
    public function processBackgroundTasks() {
        return $this->getScheduler()->processBackgroundTasks();
    }
}