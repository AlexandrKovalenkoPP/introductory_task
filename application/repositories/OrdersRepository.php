<?php

namespace app\repositories;

use app\Entity\Table\ColumnsHeader;
use app\models\Orders;

class OrdersRepository
{
    public static function getOrders($params = [])
    {
        $orders = Orders::find()
            ->andFilterWhere(['status' => $params['status']])
            ->offset(($params['page'] - 1) * 100)
            ->limit(100)
            ->all();

        return $orders;
    }

    /**
     * @return array
     */
    public static function getColumns(): array
    {
        return [
            new ColumnsHeader(Orders::getLocationId(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader('User', ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationLink(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationQuantity(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationServiceId(), ColumnsHeader::COLUMN_DROPDOWN),
            new ColumnsHeader(Orders::getLocationStatus(), ColumnsHeader::COLUMN_STRING),
//            new ColumnsHeader(Orders::getLocationMode(), ColumnsHeader::COLUMN_DROPDOWN),
            new ColumnsHeader(Orders::getLocationCreatedAt(), ColumnsHeader::COLUMN_STRING),
        ];
    }
}