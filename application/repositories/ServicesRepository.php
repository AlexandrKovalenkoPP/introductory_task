<?php

namespace app\repositories;

use app\models\Orders;
use app\models\Services;
use yii\db\Query;

class ServicesRepository
{
    public static function getServicesForFilter($status = null): array
    {
        return  (new Query())
            ->select([
                'id' => 's.id',
                'name' => 's.name',
                'amount' => 'count(o.id)'
            ])
            ->from(Orders::tableName() . ' as o')
            ->innerJoin(Services::tableName() . ' as s', 's.id = o.service_id')
            ->andFilterWhere(['o.status' => $status])
            ->groupBy(['s.id', 's.name'])
            ->orderBy('amount desc')
            ->all();
    }
}