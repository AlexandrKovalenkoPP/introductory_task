<?php

namespace app\repositories;

class Orders
{
    public static function getOrders(string $status, array $params = []): array
    {
        $orders = \app\models\Orders::find()
            ->where(['status' => $status])
            ->all();

        return $orders;
    }
}