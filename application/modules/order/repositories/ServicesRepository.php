<?php

namespace app\modules\order\repositories;

use app\modules\order\models\Orders;
use app\modules\order\models\Services;
use yii\db\Query;
use yii\helpers\Html;

class ServicesRepository
{
    public static function getServicesForFilter($status = null): array
    {
        $list = [];

        $data = (new Query())
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

//        var_dump($data);
        $allCount = array_reduce($data, function ($carry, $item) {
            return $carry + $item['amount'];
        });

        $list[] = (object) [
            'id' => null,
            'tag' => Html::tag('span', $allCount, ['class' => 'label-id']) . ' All',
        ];
        foreach ($data as $item) {
            $list[] = (object) [
                'id' => $item['id'],
                'tag' => Html::tag('span', $item['amount'], ['class' => 'label-id']) . ' ' . $item['name']
            ];
        }
//        var_dump($list); die();

        return $list;
    }
}