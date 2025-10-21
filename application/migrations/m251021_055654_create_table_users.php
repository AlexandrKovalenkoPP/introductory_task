<?php

use yii\db\Migration;

class m251021_055654_create_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(300)->notNull(),
            'last_name' => $this->string(300)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251021_055654_create_table_users cannot be reverted.\n";

        return false;
    }
}
