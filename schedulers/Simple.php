<?php

namespace pavimus\sms\schedulers;

use \pavimus\sms\models\SmsBatch;
use Yii;


class Simple extends \pavimus\sms\Scheduler {

    public function sendBatch($destinations, $text, &$jobId) {
        $model=new SmsBatch();
        $model->status=SmsBatch::STATUS_NEW;
        $model->cnt_total=count($destinations);
        $model->cnt_to_send=$model->cnt_total;
        $model->destinations=json_encode($destinations);
        $model->text=$text;
        $model->dt_created=new \yii\db\Expression('now()');
        $model->save();

        $jobId=$model->id;
    }

    public function processBackgroundTasks() {
        $endTime=time()+$this->maxWorkTime;

        $smsSent=false;

        while (time()<$endTime) {
            $trx=Yii::$app->db->beginTransaction();

            $model=SmsBatch::findBySql(
                "select * from sms_batch where status!=:status_sent order by id asc limit 1 for update",[
                    ':status_sent'=>SmsBatch::STATUS_SENT
            ])->one();

            if (!$model) {
                $trx->commit();
                break;
            }

            $destinations=json_decode($model->destinations,true);

            $destinationsCount=count($destinations);

            for($smsToProcess=$this->maxSmsToProcessInBatch;$smsToProcess>0 && $model->current_destination<$destinationsCount;$smsToProcess--) {

                $destination=$destinations[intval($model->current_destination)];
                if (!is_array($destination)) {
                    $destination=['phone'=>$destination];
                }

                $text=preg_replace_callback('%\{([a-zA-Z0-9]+)\}%', function($matches) use ($destination) {
                    return $destination[$matches[1]];
                },$model->text);

                try {
                    $this->gateway->send($destination['phone'], $text, false, $smsId);
                } catch (\Exception $e) {
                    $model->cnt_sent--;
                    $model->cnt_errors++;
                }

                $model->status=SmsBatch::STATUS_SENDING;
                $model->cnt_to_send--;
                $model->cnt_sent++;

                $model->current_destination=strval($model->current_destination+1);

                $smsSent=true;
            }

            if ($model->current_destination==$destinationsCount) {
                $model->dt_processed=new \yii\db\Expression('now()');
                $model->status=SmsBatch::STATUS_SENT;
            }

            $model->save();

            $trx->commit();
        }

        return $smsSent;
    }
}