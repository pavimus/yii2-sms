<?php

namespace pavimus\sms\models;

use Yii;
use pavimus\sms\models\base\Sms as BaseSms;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sms".
 */
class Sms extends BaseSms
{

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
