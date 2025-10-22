<?php
// ...
use app\assets\OrderPageAsset;
use app\Entity\Table\ColumnsHeader;
use app\models\Orders;
use app\repositories\ServicesRepository;use yii\helpers\Html;
use yii\helpers\Url;

// Используем наш новый бандл
OrderPageAsset::register($this);
// ...

function dropDownList(array $list): void
{
    echo Html::beginTag('th', ['class' => 'dropdown-th']);
    echo Html::beginTag('div', ['class' => 'dropdown']);

// Кнопка
    echo Html::tag('button',
            'Service' . Html::tag('span', '', ['class' => 'caret']),
            ['class' => 'btn btn-th btn-default dropdown-toggle', 'data-toggle' => 'dropdown']
    );

// Выпадающее меню
    echo Html::beginTag('ul', ['class' => 'dropdown-menu']);

// Ссылка "All"
//    $allUrl = Url::to(array_merge($baseRoute, array_diff_key($currentParams, ['service_id' => 1])));
    echo Html::tag('li', Html::a('All (N/A)', ''));

// Элементы списка
    foreach ($list as $item) {
//        var_dump($item); die();
//        $serviceParams = array_merge($currentParams, ['service_id' => $serviceId]);
//        $serviceUrl = Url::to(array_merge($baseRoute, $serviceParams));
        $content = Html::tag('span', $item['amount'], ['class' => 'label-id']);
        $content .= ' ' . $item['name'];

        echo Html::tag('li', Html::a($content, ''));
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
  <ul class="nav nav-tabs p-b">
      <?php



      // Получаем имя текущего контроллера (например, 'order')
      $controllerId = Yii::$app->controller->id;

      echo '<li'. (is_null($status) ? ' class="active"' : '') . '><a href="'. Url::to([$controllerId . '/index']) .'">All orders</a></li>';

      foreach (Orders::getStatusList()as $key => $value) {
          $actionName = strtolower(str_replace(' ', '', $value));
          $url = Url::to([$controllerId . '/' . $actionName]);
          echo '<li'. ($status === $key ? ' class="active"' : '') . '><a href="'. $url .'">'. $value . '</a></li>';
      }

      ?>
    <li class="pull-right custom-search">
      <form class="form-inline" action="/admin/orders" method="get">
        <div class="input-group">
          <input type="text" name="search" class="form-control" value="" placeholder="Search orders">
          <span class="input-group-btn search-select-wrap">

            <select class="form-control search-select" name="search-type">
              <option value="1" selected="">Order ID</option>
              <option value="2">Link</option>
              <option value="3">Username</option>
            </select>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
            </span>
        </div>
      </form>
    </li>
  </ul>

<?php
    /** Таблица */
    echo Html::beginTag('table', ['class' => 'table order-table']);

    /** Заголовок */
    echo Html::beginTag('thead');
    echo Html::beginTag('tr');

    foreach ($columns as $column) {
        echo Html::beginTag('th');

        switch ($column->type) {
            case ColumnsHeader::COLUMN_STRING:
                echo $column->header;
                break;
            case ColumnsHeader::COLUMN_DROPDOWN:
                $list = match ($column->header) {
                    Orders::getLocationServiceId() => ServicesRepository::getServicesForFilter(),
                    Orders::getLocationMode() => Orders::getModeList()
                };
//                var_dump($list); die();
                dropDownList($list);
        };

        echo Html::endTag('th');
    }

    echo Html::endTag('th');
    /** Конец заголовка */
    echo Html::endTag('thead');

    /** Тело таблицы */
    echo Html::beginTag('tbody');
    foreach ($orders as $order) {
        echo Html::beginTag('tr');

        foreach ($order as $column) {
            echo Html::beginTag('td');
            echo $column;
            echo Html::endTag('td');
        }

        echo Html::endTag('tr');
    }

    /** Конец тела таблицы */
    echo Html::endTag('tbody');


    /** Конец таблицы */
    echo Html::endTag('table');

    /** Пагинация */
    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'col-sm-8']);
    echo Html::beginTag('nav');
    echo Html::beginTag('ul', ['class' => 'pagination']);

    foreach ($pages as $page) {
        echo Html::tag('li', Html::a($page->number, ''));
    }

    echo Html::endTag('ul');
    echo Html::endTag('nav');
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'col-sm-4 pagination-counters']);
    echo "$rowStart to $rowEnd of $total";
    echo Html::endTag('div');

?>



</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
<html>