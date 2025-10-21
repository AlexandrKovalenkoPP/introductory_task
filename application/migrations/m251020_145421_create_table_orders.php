<?php

use yii\db\Migration;

class m251020_145421_create_table_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'link' => $this->string(300),
            'quantity' => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull()
                ->comment('0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail'),
            'created_at' => $this->integer(),
            'mode' => $this->tinyInteger()->notNull()
                ->comment('0 - Manual, 1 - Auto'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('orders');

        return true;
    }

}
