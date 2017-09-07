<?php

namespace pavimus\sms\models;

use yii\base\Exception;
use Yii;


class ActiveRecord extends \yii\db\ActiveRecord {

    public function save($runValidation = true, $attributes = null)
    {
        if (!parent::save($runValidation, $attributes))
            throw new Exception(json_encode($this->getErrors(),JSON_UNESCAPED_UNICODE));

        return true;
    }

}
