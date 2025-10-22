<?php

namespace app\repositories;

use app\Entity\Table\ColumnsHeader;
use app\models\Orders;
use app\models\Services;
use app\models\Users;
use yii\db\Query;

class OrdersRepository
{
    public static function getOrders($params = []): array
    {
        $orders = static::query($params)
            ->offset(($params['page'] - 1) * $params['limit'])
            ->limit($params['limit'])
            ->all();

        foreach ($orders as $key => $value) {
            $orders[$key][Orders::getLocationStatus()] = Orders::getStatusList()[$value[Orders::getLocationStatus()]];
            $orders[$key][Orders::getLocationMode()] = Orders::getModeList()[$value[Orders::getLocationMode()]];

        }

        return $orders;
    }

    public static function getAmountOrders($params = []): int
    {
        return static::query($params)->count();
    }

    /**
     * @return array
     */
    public static function getColumns(): array
    {
        return [
            new ColumnsHeader(Orders::getLocationId(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationUser(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationLink(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationQuantity(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationServiceId(), ColumnsHeader::COLUMN_DROPDOWN),
            new ColumnsHeader(Orders::getLocationStatus(), ColumnsHeader::COLUMN_STRING),
            new ColumnsHeader(Orders::getLocationMode(), ColumnsHeader::COLUMN_DROPDOWN),
            new ColumnsHeader(Orders::getLocationCreatedAt(), ColumnsHeader::COLUMN_STRING),
        ];
    }

    public static function query($params = []): Query
    {
        $query = (new Query())
            ->select([
                Orders::getLocationId() => 'orders.id',
                Orders::getLocationUser() => 'concat(users.first_name, " ", users.last_name)',
                Orders::getLocationLink() => 'orders.link',
                Orders::getLocationQuantity() => 'orders.quantity',
                Orders::getLocationServiceId() => 'services.name',
                Orders::getLocationStatus() => 'orders.status',
                Orders::getLocationCreatedAt() => "orders.created_at",
                Orders::getLocationMode() => 'orders.mode',
            ])
            ->from('orders')
            ->innerJoin(Services::tableName() . ' services', 'services.id = orders.service_id')
            ->innerJoin(Users::tableName() . ' users', 'users.id = orders.user_id');

        if (isset($params['status'])) $query->andWhere(['orders.status' => $params['status']]);

        if (isset($params['search'])) {
            match ($params['search-type']) {
                'id' => $query->andWhere(['orders.id' => $params['search']]),
                'link' => $query->andWhere(['like', 'orders.link', $params['search']]),
                'user' => $query
                    ->orWhere(['like', 'users.first_name', $params['search']])
                    ->orWhere(['like', 'users.last_name', $params['search']]),
            };
        }

        if (isset($params['service_id'])) $query->andWhere(['services.id' => $params['service_id']]);
        if (isset($params['mode'])) $query->andWhere(['orders.mode' => $params['mode']]);

        return $query;
    }
}