<?php

namespace order\models;

use yii\db\ActiveRecord;

/**
 * # Модель таблицы 'services'
 *
 * @property int    id
 * @property string name
 */
class Services extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'services';
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
        ];
    }
}