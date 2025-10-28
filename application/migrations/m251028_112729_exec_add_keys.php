<?php

use order\components\MigrateSQL;

class m251028_112729_exec_add_keys extends MigrateSQL
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->executeSqlFile('/migrations/add_keys.sql');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251028_112729_exec_add_keys cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251028_112729_exec_add_keys cannot be reverted.\n";

        return false;
    }
    */
}
