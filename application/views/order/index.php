<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список заказов';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider, // Переданный из контроллера провайдер
        // 'filterModel' => $searchModel, // Используется, если есть модель поиска

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], // Нумерация строк

            // 1. Простая колонка (совпадает с именем атрибута модели)
            'id',

            // 2. Колонка с форматированием (например, для ссылки)
            [
                'attribute' => 'link',
                'format' => 'url', // Превратит текст в кликабельную ссылку
                'contentOptions' => ['style' => 'max-width: 250px; overflow: hidden;'],
            ],

            // 3. Колонка для связи (вывод имени пользователя вместо ID)
            [
                'attribute' => 'user_id',
                'label' => 'Клиент',
                'value' => 'user.first_name', // Использует связь getUser() из модели Order
            ],

            // 4. Колонка для tinyint/статуса с использованием констант
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status === $model::STATUS_COMPLETED ? 'Завершён' : 'В работе';
                },
            ],

            // 5. Колонка для временной метки Unix
            [
                'attribute' => 'created_at',
                'format' => 'datetime', // Форматирует timestamp в читаемую дату/время
            ],

            // Колонка с кнопками действий (View, Update, Delete)
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>