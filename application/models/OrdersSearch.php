<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrdersSearch extends Orders
{
    public function rules(): array
    {
        return [
            [['id', 'user_id', 'quantity', 'service_id', 'status', 'mode'], 'integer'],
            [['link'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params): ActiveDataProvider
    {
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
//            'id' => $this->id,
//            'status' => $this->status,
//            'user_id' => $this->user_id,
            'service_id' => $this->service_id,
            'mode' => $this->mode,
        ]);

        return $dataProvider;
    }
}