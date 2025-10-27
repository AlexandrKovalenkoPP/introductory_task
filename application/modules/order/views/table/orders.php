<?php
/**
 * @var stdClass $result
 * @var Pagination $pages
 * @var array $tabs
 * @var object $searchModel
 * @var string $status
 */

use order\assets\OrderPageAsset;
use order\components\Table\ColumnsHeader;
use order\components\Table\DropDownList;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = ['js' => []];

OrderPageAsset::register($this);

$currentAction = Yii::$app->controller->action->id;
$controllerId = Yii::$app->controller->id;
$baseRoute = [$controllerId . '/' . $currentAction];
$currentParams = Yii::$app->request->queryParams;

$this->title = 'Orders';

$this->registerCss("
.label-default {
  border: 1px solid #ddd;
  background: none;
  color: #333;
  min-width: 30px;
  display: inline-block;
}
");

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


function getQueryParams() {
    const params = {};
    window.location.search
        .substring(1)
        .split("&")
        .forEach(pair => {
            if (!pair) return;
            const [key, value] = pair.split("=");
            params[decodeURIComponent(key)] = decodeURIComponent(value || '');
        });
    return params;
}

const prevParams = JSON.parse(sessionStorage.getItem('prevParams') || '{}');
const currentParams = getQueryParams();

let resetPage = false;

for (const key in currentParams) {
    if (key === 'page') continue;

    if (!(key in prevParams) || prevParams[key] !== currentParams[key]) {
        resetPage = true;
        break;
    }
}

if (resetPage) {
    const newParams = { ...currentParams };
    delete newParams.page;

    const queryString = Object.keys(newParams)
        .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(newParams[key]))
        .join('&');

    const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
    
    sessionStorage.setItem('prevParams', JSON.stringify(newParams));

    window.location.href = newUrl;
} else {
    sessionStorage.setItem('prevParams', JSON.stringify(currentParams));
}

JS);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>

<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="bs-navbar-collapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <?= Html::a('Orders', '#') ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 60px;">
    <ul class="nav nav-tabs p-b">
        <?php foreach ($tabs as $tab): ?>
            <li class="<?= $tab['slug'] === $status ? 'active' : '' ?>">
                <?= Html::a($tab['label'], $tab['url']) ?>
            </li>
        <?php endforeach; ?>

        <li class="pull-right custom-search">
            <?= Html::beginForm([$this->context->id . '/index'], 'get', ['class' => 'form-inline', 'id' => 'search-form']) ?>
            <div class="input-group">
                <?= Html::input(
                'text',
                'search',
                $searchModel->search !== null ? urldecode($searchModel->search) : '',
                ['class' => 'form-control', 'placeholder' => 'Search orders']) ?>
                <span class="input-group-btn search-select-wrap">
                    <?= Html::dropDownList('searchType', $searchModel->searchType, $searchModel->getSearchOptions(), [
                            'class' => 'form-control search-select'
                    ]) ?>
                    <?= Html::submitButton(Html::tag('span', '', ['class' => 'glyphicon glyphicon-search']), [
                            'class' => 'btn btn-default'
                    ]) ?>
                </span>
            </div>
            <?= Html::endForm() ?>
        </li>
    </ul>

    <table class="table order-table">
        <thead>
        <tr>
            <?php foreach ($result->columns as $column): ?>
                <?php if ($column->type === ColumnsHeader::COLUMN_STRING): ?>
                    <th><?= Html::encode($column->header) ?></th>
                <?php elseif ($column->type === ColumnsHeader::COLUMN_DROPDOWN): ?>
                    <?php DropDownList::dropDownList(
                            $column->header,
                            $column->list,
                            $currentParams,
                            $baseRoute,
                            $column->key
                    ); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result->data as $order): ?>
            <tr>
                <?php foreach ($result->columns as $column): ?>
                    <td>
                        <?php if ($column->key === 'created'): ?>
                            <?php $date = (new DateTime())->setTimestamp($order[$column->key]); ?>
                            <span class="nowrap"><?= $date->format('Y-m-d') ?></span>
                            <span class="nowrap"><?= $date->format('H:i:s') ?></span>
                        <?php else: ?>
                            <?= Html::encode($order[$column->key]) ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-sm-8">
            <?= LinkPager::widget(['pagination' => $pages]) ?>
        </div>
        <div class="col-sm-4 pagination-counters text-right">
            <?= Yii::t('order-module', '{start} to {end} of {total}', [
                    'start' => $result->footer->start,
                    'end' => $result->footer->end,
                    'total' => $result->total,
            ]) ?>
        </div>
    </div>

</div>

    <?php
    $exportRoute = ['export/export-orders-from-table-csv'];
    $exportUrl = Url::to(array_merge($exportRoute, $currentParams));
    ?>

        <div class="text-right" style="margin: 10px;">
            <?= Html::a(
                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-download-alt']) . ' Export CSV',
                    $exportUrl,
                    ['class' => 'btn btn-primary']
            ) ?>
        </div>
    </div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
