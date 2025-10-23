<?php
// ...
use app\assets\OrderPageAsset;
use app\Entity\Table\ColumnsHeader;
use app\modules\order\models\Orders;
use app\repositories\ServicesRepository;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

OrderPageAsset::register($this);

$currentAction = Yii::$app->controller->action->id;
$controllerId = Yii::$app->controller->id;
$baseRoute = [$controllerId . '/' . $currentAction];
$currentParams = Yii::$app->request->queryParams;

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

    echo Html::tag('li', Html::a('All (N/A)', $allUrl), ['class' => ($isAllActive ? 'active' : '')]);

    foreach ($list as $id => $data) {
        $itemParams = array_merge($currentParams, [$attribute => $id]);
        $itemUrl = Url::to(array_merge($baseRoute, $itemParams));

        if (is_array($data)) {
             $content = Html::tag('span', $data['amount'] ?? $id, ['class' => 'label-id']) . ' orders.php' . ($data['name'] ?? $id);
        } else {
             $content = $data;
        }

        $isActive = ($activeValue == $id);

        echo Html::tag('li', Html::a($content, $itemUrl), ['class' => ($isActive ? 'active' : '')]);
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
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
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

        echo Html::tag('li', Html::a('All orders', Url::to([$controllerId.'/index'])), ['class' => (is_null($status) ? 'active' : '')]);
        foreach (Orders::getStatusList()as $key => $value) {
            $slug = strtolower(str_replace(' ', '', $value));
            $url = Url::to(["$controllerId/$slug"]);
            echo Html::tag('li', Html::a($value, $url), ['class' => ($status == $key ? 'active' : '')]);
        }

        $searchQuery = Yii::$app->request->get('search', '');
        $searchType = Yii::$app->request->get('search-type', 'id');

        $searchOptions = [
            'id' => 'Order ID',
            'link' => 'Link',
            'user' => 'Username',
        ];

        echo Html::beginTag('li', ['class' => 'pull-right custom-search']);
        echo Html::beginForm($currentAction, 'get', ['class' => 'form-inline']);
        echo Html::beginTag('div', ['class' => 'input-group']);
        echo Html::input('text', 'search', $searchQuery, ['class' => 'form-control', 'placeholder' => 'Search orders']);
        echo Html::beginTag('span', ['class' => 'input-group-btn search-select-wrap']);
        echo Html::dropDownList('search-type', $searchType, $searchOptions, ['class' => 'form-control search-select']);
        echo Html::submitButton(
          Html::tag('span', '', ['class' => 'glyphicon glyphicon-search', 'aria-hidden' => 'true']),
          ['class' => 'btn btn-default']);
        echo Html::endTag('span');
        echo Html::endTag('div');
        echo Html::endForm();
        echo Html::endTag('li');

    echo Html::endTag('ul');

    $exportRoute = ['export/export-csv'];
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

    foreach ($columns as $column) {
        switch ($column->type) {
            case ColumnsHeader::COLUMN_STRING:
                echo Html::beginTag('th');
                echo $column->header;
                echo Html::endTag('th');
                break;
            case ColumnsHeader::COLUMN_DROPDOWN:

                $list = null;
                $attribute = null;

                // Определяем список и имя GET-параметра
                $header = $column->header;
                if ($header == Orders::getLocationServiceId()) {
                    $attribute = 'service_id'; // GET-параметр для Service
                    $list = ServicesRepository::getServicesForFilter();
                } elseif ($header == Orders::getLocationMode()) {
                    $attribute = 'mode'; // GET-параметр для Mode
                    $list = Orders::getModeList();
                }

                // Вызываем обновлённую функцию с параметрами контекста
                dropDownList($column->header, $list, $currentParams, $baseRoute, $attribute);
                break;
        };
    }

    echo Html::endTag('th');
    /** Конец заголовка */
    echo Html::endTag('thead');

    /** Тело таблицы */
    echo Html::beginTag('tbody');
    foreach ($orders as $order) {
        echo Html::beginTag('tr');

        foreach ($columns as $column) {
            echo Html::beginTag('td');
            if ($column->header == Orders::getLocationCreatedAt()) {
                $date = (new DateTime())->setTimestamp($order[$column->header]);
                echo Html::tag('span', $date->format('Y-m-d'), ['class' => 'nowrap']);
                echo Html::tag('span', $date->format('H:i:s'), ['class' => 'nowrap']);
            } else {
                echo $order[$column->header];
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
    echo "$rowStart to $rowEnd of $total";
    echo Html::endTag('div');

    echo Html::endTag('div');


?>



</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
<html>