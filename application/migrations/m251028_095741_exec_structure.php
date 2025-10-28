<?php

use order\components\MigrateSQL;

class m251028_095741_exec_structure extends MigrateSQL
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->executeSqlFile('/migrations/test_db_structure.sql');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251028_095741_exec_structure cannot be reverted.\n";

        return false;
    }
}
