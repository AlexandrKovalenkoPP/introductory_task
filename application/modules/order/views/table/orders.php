<?php

/**
 * @var stdClass $result
 * @var $status
 * @var Pagination $pages
 */

use app\components\Table\ColumnsHeader;
use app\modules\order\assets\OrderPageAsset;
use app\modules\order\models\Orders;
use app\modules\order\Module;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

OrderPageAsset::register($this);

$currentAction = Yii::$app->controller->action->id;
$controllerId = Yii::$app->controller->id;
$baseRoute = [$controllerId . '/' . $currentAction];
$currentParams = Yii::$app->request->queryParams;

/**
 * Добавляем очистку данных в GET
 */
$this->registerJs(<<<JS
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#search-form');
  if (!form) return;

  form.addEventListener('submit', () => {
    form.querySelectorAll('input[name], select[name]').forEach(el => {
      if (el.value.trim() === '') el.removeAttribute('name');
    });
  });
});
JS);

/**
 * Генерирует HTML для выпадающего меню фильтрации.
 * @param string $title Метка кнопки ('Service', 'Mode').
 * @param array $list Данные для списка [id => ['name' => 'Name', 'amount' => 123]] или [id => 'Name'].
 * @param string $attribute Имя GET-параметра (напр. 'service_id' или 'mode').
 */
function dropDownList(string $title, array $list, array $currentParams, array $baseRoute, string $attribute): void
{
    $activeValue = $currentParams[$attribute] ?? null;

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
    $allUrl = Url::to(array_merge($baseRoute, $allParams));

    $isAllActive = is_null($activeValue) || $activeValue === '';

    foreach ($list as $id => $data) {
        $itemParams = array_merge($currentParams, [$attribute => $data->id]);
        $itemUrl = Url::to(array_merge($baseRoute, $itemParams));
        $isActive = ($activeValue == $id);
        echo Html::tag('li', Html::a($data->tag, $itemUrl), ['class' => ($isActive ? 'active' : '')]);
    }

    echo Html::endTag('ul');
    echo Html::endTag('div');
    echo Html::endTag('th');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style>
    .label-default{
      border: 1px solid #ddd;
      background: none;
      color: #333;
      min-width: 30px;
      display: inline-block;
    }
  </style>
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Orders</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
<?php
    echo Html::beginTag('ul', ['class' => 'nav nav-tabs p-b']);

        echo Html::tag('li', Html::a(Yii::t(Module::I18N_CATEGORY, 'All orders'), Url::to([$controllerId.'/index'])), ['class' => (is_null($status) ? 'active' : '')]);
        foreach (Orders::getStatusList()as $key => $value) {
            $slug = strtolower(str_replace(' ', '', $value));
            $url = Url::to(["$controllerId/$slug"]);
            echo Html::tag(
                    'li',
                    Html::a(Yii::t(Module::I18N_CATEGORY, $value), $url),
                    ['class' => ($status == $key ? 'active' : '')]
            );
        }

        $searchQuery = Yii::$app->request->get('search', '');
        $searchType = Yii::$app->request->get('searchType', 'id');

        $searchOptions = [
            'id' => 'Order ID',
            'link' => 'Link',
            'user' => 'Username',
        ];

        echo Html::beginTag('li', ['class' => 'pull-right custom-search']);
        echo Html::beginForm($baseRoute, 'get', ['class' => 'form-inline', 'id' => 'search-form']);
        echo Html::beginTag('div', ['class' => 'input-group']);
        echo Html::input('text', 'search', $searchQuery, ['class' => 'form-control', 'placeholder' => 'Search orders']);
        echo Html::beginTag('span', ['class' => 'input-group-btn search-select-wrap']);
        echo Html::dropDownList('searchType', $searchType, $searchOptions, ['class' => 'form-control search-select']);
        echo Html::submitButton(
          Html::tag('span', '', ['class' => 'glyphicon glyphicon-search', 'aria-hidden' => 'true']),
          ['class' => 'btn btn-default']);
        echo Html::endTag('span');
        echo Html::endTag('div');
        echo Html::endForm();
        echo Html::endTag('li');

    echo Html::endTag('ul');

    $exportRoute = ['export/export-orders-from-table-csv'];
    $exportUrl = Url::to(array_merge($exportRoute, $currentParams));
    echo Html::tag('div', Html::a(
        Html::tag('span', '', ['class' => 'glyphicon glyphicon-download-alt']) . ' Export CSV',
        $exportUrl,
        ['class' => 'btn btn-primary pull-right', 'style' => 'margin-top: -35px;']),
        ['class' => 'clearfix']
    );

    /** Таблица */
    echo Html::beginTag('table', ['class' => 'table order-table']);

    /** Заголовок */
    echo Html::beginTag('thead');
    echo Html::beginTag('tr');

    foreach ($result->columns as $column) {
        switch ($column->type) {
            case ColumnsHeader::COLUMN_STRING:
                echo Html::beginTag('th');
                echo $column->header;
                echo Html::endTag('th');
                break;
            case ColumnsHeader::COLUMN_DROPDOWN:
                dropDownList($column->header, $column->list, $currentParams, $baseRoute, $column->key);
                break;
        };
    }

    echo Html::endTag('th');
    /** Конец заголовка */
    echo Html::endTag('thead');

    /** Тело таблицы */
    echo Html::beginTag('tbody');
    foreach ($result->data as $order) {
        echo Html::beginTag('tr');

        foreach ($result->columns as $column) {
            echo Html::beginTag('td');
            if ($column->key == 'created') {
                $date = (new DateTime())->setTimestamp($order[$column->key]);
                echo Html::tag('span', $date->format('Y-m-d'), ['class' => 'nowrap']);
                echo Html::tag('span', $date->format('H:i:s'), ['class' => 'nowrap']);
            } else {
                echo $order[$column->key];
            }
            echo Html::endTag('td');
        }

        echo Html::endTag('tr');
    }

    /** Конец тела таблицы */
    echo Html::endTag('tbody');


    /** Конец таблицы */
    echo Html::endTag('table');

    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'col-sm-8']);

    /** Пагинация */
    echo Html::beginTag('nav');
    echo Html::beginTag('ul', ['class' => 'pagination']);

    echo LinkPager::widget(['pagination' => $pages]);

    echo Html::endTag('ul');
    echo Html::endTag('nav');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'col-sm-4 pagination-counters']);
    echo Yii::t(
            'order-module',
            '{start} to {end} of {total}',
            [
                'start' => $result->footer->start,
                'end' => $result->footer->end,
                'total' => $result->total
            ]
    );
    echo Html::endTag('div');

    echo Html::endTag('div');


?>



</div>
<!--<script src="js/jquery.min.js"></script>-->
<!--<script src="js/bootstrap.min.js"></script>-->
</body>
<html>