<?php

use yii\db\Migration;

class m171017_061035_add_sms_table extends Migration
{
    public function safeUp()
    {
        $this->execute("
            CREATE SEQUENCE sms_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
        ");

        $this->execute('
            CREATE TABLE "public"."sms" (
                "id" bigint DEFAULT nextval(\'sms_id_seq\') NOT NULL,
                "sms_batch_id" bigint NOT NULL,
                "dt" timestamptz NOT NULL,
                "phone" text NOT NULL,
                "text" text NOT NULL,
                "parts" smallint NOT NULL,
                CONSTRAINT "sms_sms_batch_id_fkey" FOREIGN KEY (sms_batch_id) REFERENCES sms_batch(id) NOT DEFERRABLE
            ) WITH (oids = false);
        ');

        $this->execute("
            ALTER TABLE \"sms_batch\"
            ALTER \"id\" TYPE bigint,
            ALTER \"id\" SET DEFAULT nextval('sms_batch_id_seq'),
            ALTER \"id\" SET NOT NULL;
        ");

        $this->execute('
            ALTER TABLE "sms"
            ALTER "sms_batch_id" TYPE bigint,
            ALTER "sms_batch_id" DROP DEFAULT,
            ALTER "sms_batch_id" DROP NOT NULL;
        ');

        $this->execute('
            ALTER TABLE "sms"
            ADD "service_sms_id" text NULL;
        ');

        $this->execute('
            ALTER TABLE "sms"
            ADD "error" text NULL;
        ');
    }

    public function safeDown()
    {
        echo "m171017_061035_add_sms_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171017_061035_add_sms_table cannot be reverted.\n";

        return false;
    }
    */
}
