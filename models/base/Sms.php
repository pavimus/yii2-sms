<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace pavimus\sms\models\base;

use Yii;

/**
 * This is the base-model class for table "sms".
 *
 * @property integer $id
 * @property integer $sms_batch_id
 * @property string $dt
 * @property string $phone
 * @property string $text
 * @property integer $parts
 * @property string $service_sms_id
 * @property string $error
 *
 * @property \app\models\SmsBatch $smsBatch
 * @property string $aliasModel
 */
abstract class Sms extends \pavimus\sms\models\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sms_batch_id', 'parts'], 'integer'],
            [['dt', 'phone', 'text', 'parts'], 'required'],
            [['dt'], 'safe'],
            [['phone', 'text', 'service_sms_id', 'error'], 'string'],
            [['sms_batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\SmsBatch::className(), 'targetAttribute' => ['sms_batch_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sms_batch_id' => 'Sms Batch ID',
            'dt' => 'Dt',
            'phone' => 'Phone',
            'text' => 'Text',
            'parts' => 'Parts',
            'service_sms_id' => 'Service Sms ID',
            'error' => 'Error',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsBatch()
    {
        return $this->hasOne(\app\models\SmsBatch::className(), ['id' => 'sms_batch_id']);
    }




}