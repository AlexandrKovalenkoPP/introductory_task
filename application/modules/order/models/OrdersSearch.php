<?php

namespace order\models;

use order\Module;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Поисковая модель таблицы для заказов
 */
class OrdersSearch extends Model
{
    const string ID = 'id';
    const string LINK = 'link';
    const string USER = 'user';

    public ?string $search = null;
    public ?string $searchType = 'id';
    public ?string $status = null;

    public function rules(): array
    {
        return [
            [['search', 'searchType'], 'safe'],
            [['status'], 'string'],
        ];
    }

    /**
     * Вкладки со статусами заказов над таблицей
     *
     * @param string $controllerId
     * @return array
     */
    public function getTabs(string $controllerId): array
    {
        $tabs = [];

        $tabs[] = [
            'label' => Yii::t(Module::I18N_CATEGORY, 'All orders'),
            'url' => Url::to(["$controllerId/index"]),
            'slug' => null
        ];

        foreach (Orders::getStatusList() as $key => $value) {
            $slug = strtolower(str_replace(' ', '', $value));
            $tabs[] = [
                'label' => Yii::t(Module::I18N_CATEGORY, $value),
                'url' => Url::to(["$controllerId/$slug"]),
                'slug' => $slug
            ];
        }

        return $tabs;
    }

    /**
     * Список опций для выбора типа поиска
     *
     * @return string[]
     */
    public function getSearchOptions(): array
    {
        return [
            self::ID => 'Order ID',
            self::LINK => 'Link',
            self::USER => 'Username'
        ];
    }
}