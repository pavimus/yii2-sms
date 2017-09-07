<?php

namespace pavimus\sms\models;

use Yii;
use pavimus\sms\models\base\SmsBatch as BaseSmsBatch;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sms_batch".
 */
class SmsBatch extends BaseSmsBatch
{
    const STATUS_NEW=0;
    const STATUS_SENDING=1;
    const STATUS_SENT=2;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
             parent::rules(),
             [
                  # custom validation rules
             ]
        );
    }
}
