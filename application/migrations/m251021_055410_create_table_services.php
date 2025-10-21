<?php

use yii\db\Migration;

class m251021_055410_create_table_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('services', [
            'id' => $this->primaryKey(),
            'name' => $this->string(300)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('services');

        return true;
    }
}
