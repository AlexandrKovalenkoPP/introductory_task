<?php

namespace app\modules\order\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * # Модель таблицы 'orders'
 *
 * @property int        id
 * @property int        user_id
 * @property string     link
 * @property int        quantity
 * @property int        service_id
 * @property int        status
 * @property int        created_at
 * @property int        mode
 * @property Users      $users
 * @property Services   $services
 */
class Orders extends ActiveRecord
{
    /** Статус "В ожидании" */
    const int STATUS_PENDING = 0;
    /** Статус "В процессе" */
    const int STATUS_IN_PROGRESS = 1;
    /** Статус "Выполнен" */
    const int STATUS_COMPLETED = 2;
    /** Статус "Отменен" */
    const int STATUS_CANCELED = 3;
    /** Статус "Ошибка" */
    const int STATUS_FAIL = 4;

    /** Мод "Ручной" */
    const int MODE_MANUAL = 0;
    /** Мод "Автоматический" */
    const int MODE_AUTO = 1;

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'orders';
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => self::getLocationId(),
            'user_id' => self::getLocationUser(),
            'link' => self::getLocationLink(),
            'quantity' => self::getLocationQuantity(),
            'service_id' => self::getLocationServiceId(),
            'status' => self::getLocationStatus(),
            'created_at' => self::getLocationCreatedAt(),
            'mode' => self::getLocationMode(),
        ];
    }

    /**
     * @return string
     */
    public static function getLocationId(): string
    {
        return Yii::t('order-module', 'ID');
    }

    public static function getLocationUser(): string
    {
        return Yii::t('order-module', 'User');
    }

    public static function getLocationLink(): string
    {
        return Yii::t('order-module', 'Link');
    }

    public static function getLocationQuantity(): string
    {
        return Yii::t('order-module', 'Quantity');
    }

    public static function getLocationServiceId(): string
    {
        return Yii::t('order-module', 'Service');
    }

    public static function getLocationStatus(): string
    {
        return Yii::t('order-module', 'Status');
    }

    public static function getLocationCreatedAt(): string
    {
        return Yii::t('order-module', 'Created');
    }

    public static function getLocationMode(): string
    {
        return Yii::t('order-module', 'Mode');
    }

    public function rules(): array
    {
        return [
            [['user_id', 'link', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'required'],
            [['user_id', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['link'], 'string', 'max' => 300],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_IN_PROGRESS, self::STATUS_COMPLETED, self::STATUS_CANCELED, self::STATUS_FAIL]],
            ['mode', 'in', 'range' => [self::MODE_MANUAL, self::MODE_AUTO]],
        ];
    }

    /**
     * Связь с пользователем
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * Связь с сервисом
     *
     * @return ActiveQuery
     */
    public function getService(): ActiveQuery
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELED => 'Cancelled',
            self::STATUS_FAIL => 'Fail',
        ];
    }

    public static function getModeList(): array
    {
        return [
            self::MODE_MANUAL => 'Manual',
            self::MODE_AUTO => 'Auto',
        ];
    }
}