<?php

namespace pavimus\sms;


/**
 * Class Sms
 * @package pavimus\sms
 */
class Sms extends \yii\base\Object
{
    /**
     * Sms service configuration
     *
     * @var array
     */
    protected $service;

    /**
     * Initialize the component.
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        /*
        if (!$this->service) {
            throw new \yii\base\ErrorException(\Yii::t('sms', 'Services are not configured.'));
        }
        */

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
     * send sms
     */
    public function sendSms($phone,$text) {
        die('ok');
    }

}