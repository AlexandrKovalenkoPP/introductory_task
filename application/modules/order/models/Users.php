<?php

namespace order\models;

use yii\db\ActiveRecord;

/**
 * # Модель таблицы 'users'
 *
 * @property int    id
 * @property string first_name
 * @property string lastname
 */
class Users extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'trim'],
            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 300],
        ];
    }
}