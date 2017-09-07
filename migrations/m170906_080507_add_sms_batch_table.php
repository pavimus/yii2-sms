<?php

use yii\db\Migration;

class m170906_080507_add_sms_batch_table extends Migration
{
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE "sms_batch" (
                "id" integer DEFAULT nextval(\'sms_batch_id_seq\') NOT NULL,
                "status" smallint DEFAULT 0 NOT NULL,
                "cnt_total" integer,
                "cnt_to_send" integer,
                "cnt_sent" integer DEFAULT 0 NOT NULL,
                "cnt_errors" integer DEFAULT 0 NOT NULL,
                "destinations" jsonb NOT NULL,
                "current_destination" text,
                "text" text NOT NULL,
                "dt_created" timestamptz NOT NULL,
                "dt_processed" timestamptz
            ) WITH (oids = false);
        ');

        $this->execute('
            ALTER TABLE "sms_batch"
            ADD CONSTRAINT "sms_batch_id" PRIMARY KEY ("id");
            CREATE INDEX "sms_batch_status_id" ON "sms_batch" ("status", "id");
        ');
    }

    public function safeDown()
    {
        echo "m170906_080507_add_sms_batch_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170906_080507_add_sms_batch_table cannot be reverted.\n";

        return false;
    }
    */
}
