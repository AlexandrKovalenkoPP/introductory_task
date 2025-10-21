<?php

use yii\db\Migration;

class m251021_071218_alter_tables_add_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        /** Внешний ключ до пользователя */
        $this->addForeignKey(
            'fk-orders-users',
            'orders',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        /** Внешний ключ до сервисов */
        $this->addForeignKey(
            'fk-orders-services',
            'orders',
            'service_id',
            'services',
            'id',
            'CASCADE'
        );

        /** Индексирование заказов по статусу */
        $this->createIndex('idx-orders-status', 'orders', 'status');
        $this->createIndex('idx-orders-services', 'orders', 'service_id');
        $this->createIndex('idx-orders-users', 'orders', 'user_id');
        $this->createIndex('idx-orders-status-links', 'orders', ['status', 'link']);
        $this->createIndex('idx-orders-status-service-id', 'orders', ['status', 'service_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-orders-status', 'orders');
        $this->dropIndex('idx-orders-services', 'orders');
        $this->dropIndex('idx-orders-users', 'orders');
        $this->dropIndex('idx-orders-status-links', 'orders');
        $this->dropIndex('idx-orders-status-service-id', 'orders');
        $this->dropForeignKey('fk-orders-users', 'orders');
        $this->dropForeignKey('fk-orders-services', 'orders');

        return true;
    }
}
