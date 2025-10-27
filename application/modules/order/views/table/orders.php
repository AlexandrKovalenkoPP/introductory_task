<?php

/**
 * @var stdClass $result
 * @var Pagination $pages
 * @var $tabs
 * @var $searchModel
 * @var $status
 */

use order\assets\OrderPageAsset;
use order\components\Table\ColumnsHeader;
use order\components\Table\DropDownList;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

OrderPageAsset::register($this);

$currentAction = Yii::$app->controller->action->id;
$controllerId = Yii::$app->controller->id;
$baseRoute = [$controllerId . '/' . $currentAction];
$currentParams = Yii::$app->request->queryParams;

$css = <<<CSS
.label-default {
  border: 1px solid #ddd;
  background: none;
  color: #333;
  min-width: 30px;
  display: inline-block;
}
CSS;

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

echo '<!DOCTYPE html>';
echo Html::beginTag('html', ['lang' => 'en']);

echo Html::beginTag('head');
echo Html::tag('meta', ['charset' => 'utf-8']);
echo Html::tag('meta', ['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);
echo Html::tag('meta', ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
echo Html::tag('title', '');
echo Html::style($css);
echo Html::endTag('head');

echo Html::beginTag('body');
echo Html::beginTag('nav', ['class' => 'navbar navbar-fixed-top navbar-default']);
echo Html::beginTag('div', ['class' => 'container-fluid']);
echo Html::beginTag('div', ['class' => 'navbar-header']);
echo Html::button(
        Html::tag('span', 'Toggle navigation', ['class' => 'sr-only']) .
        Html::tag('span', '', ['class' => 'icon-bar']) .
        Html::tag('span', '', ['class' => 'icon-bar']) .
        Html::tag('span', '', ['class' => 'icon-bar']),
        [
                'type' => 'button',
                'class' => 'navbar-toggle collapsed',
                'data-toggle' => 'collapse',
                'data-target' => '#bs-navbar-collapse'
        ]
);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'collapse navbar-collapse', 'id' => 'bs-navbar-collapse']);
echo Html::beginTag('ul', ['class' => 'nav navbar-nav']);
echo Html::tag('li',
        Html::a('Orders', '#'),
        ['class' => 'active']
);
echo Html::endTag('ul');
echo Html::endTag('div');

echo Html::endTag('div');
echo Html::endTag('nav');

echo Html::beginTag('div', ['class' => 'container-fluid']);
echo Html::beginTag('ul', ['class' => 'nav nav-tabs p-b']);

foreach ($tabs as $tab) {
    echo Html::beginTag('li', ['class' => $tab['slug'] === $status ? 'active' : '']);
    echo Html::a($tab['label'], $tab['url']);
    echo Html::endTag('li');
}

echo Html::beginTag('li', ['class' => 'pull-right custom-search']);
echo Html::beginForm([$this->context->id . '/index'], 'get', ['class' => 'form-inline', 'id' => 'search-form']);
echo Html::beginTag('div', ['class' => 'input-group']);
echo Html::input('text', 'search', $searchModel->search, ['class' => 'form-control', 'placeholder' => 'Search orders']);
echo Html::beginTag('span', ['input-group-btn search-select-wrap']);
echo Html::dropDownList('searchType', $searchModel->searchType, $searchModel->getSearchOptions(), ['class' => 'form-control search-select']);
echo Html::submitButton(Html::tag('span', '', ['class' => 'glyphicon glyphicon-search']), ['class' => 'btn btn-default']);
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

echo Html::beginTag('table', ['class' => 'table order-table']);
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
            DropDownList::dropDownList($column->header, $column->list, $currentParams, $baseRoute, $column->key);
            break;
    };
}
echo Html::endTag('tr');
echo Html::endTag('thead');
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
echo Html::endTag('tbody');
echo Html::endTag('table');

echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-sm-8']);
echo LinkPager::widget(['pagination' => $pages]);
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

echo Html::endTag('div');
echo Html::endTag('body');
echo Html::endTag('html');

?>