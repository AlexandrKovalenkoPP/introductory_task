<?php

namespace order\components\Table;

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Компонент для формирования колонки с выпадающим меню
 */
class DropDownList
{
    /**
     * Генерирует HTML для выпадающего меню фильтрации.
     * @param string $title Метка кнопки ('Service', 'Mode').
     * @param array $list Данные для списка [id => ['name' => 'Name', 'amount' => 123]] или [id => 'Name'].
     * @param string $attribute Имя GET-параметра (напр. 'service_id' или 'mode').
     * @param array $currentParams
     * @param array $baseRoute
     * @return void
     */
    public static function dropDownList(string $title, array $list, array $currentParams, array $baseRoute, string $attribute): void
    {
        $activeValue = null;
        if (isset($currentParams[$attribute])) {
            $activeValue = (int)$currentParams[$attribute];
        }

        echo Html::beginTag('th', ['class' => 'dropdown-th']);
        echo Html::beginTag('div', ['class' => 'dropdown']);

        echo Html::tag('button',
            $title . Html::tag('span', '', ['class' => 'caret']),
            ['class' => 'btn btn-th btn-default dropdown-toggle', 'data-toggle' => 'dropdown']
        );

        echo Html::beginTag('ul', ['class' => 'dropdown-menu']);

        $allParams = $currentParams;
        unset($allParams[$attribute]);
        $allParams = array_filter($allParams, function($value) { return $value !== null && $value !== ''; });
        unset($allParams['page']);

        foreach ($list as $id => $data) {
            $itemParams = array_merge($currentParams, [$attribute => $data['id']]);
            $itemUrl = Url::to(array_merge($baseRoute, $itemParams));
            $isActive = ($activeValue === $data['id']);
            echo Html::tag('li', Html::a($data['tag'], $itemUrl), ['class' => ($isActive ? 'active' : '')]);
        }

        echo Html::endTag('ul');
        echo Html::endTag('div');
        echo Html::endTag('th');
    }
}