<?php


use order\components\MigrateSQL;

class m251028_112315_exec_dump extends MigrateSQL
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->executeSqlFile('/migrations/test_db_data.sql');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251028_112315_exec_dump cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251028_112315_exec_dump cannot be reverted.\n";

        return false;
    }
    */
}
