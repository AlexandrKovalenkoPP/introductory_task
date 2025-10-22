<?php

namespace app\repositories;

use app\models\Orders;

class OrdersRepository
{
    public static function getOrders(string|null $status = null, array $params = [])
    {
        $orders = Orders::find()
            ->andFilterWhere(['status' => $status])
            ->limit(10)
            ->all();

        return $orders;
    }
}