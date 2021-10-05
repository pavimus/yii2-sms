<?php

namespace pavimus\sms;


abstract class Scheduler extends \yii\base\BaseObject {
    public $gateway;

    /**
     * @var int max work time in seconds
     */
    public $maxWorkTime=60;

    /**
     * @var int how much sms send before saving current state to db
     */
    public $maxSmsToProcessInBatch=10;

    public abstract function sendBatch($destinations, $text, &$jobId);

    public abstract function processBackgroundTasks();
}