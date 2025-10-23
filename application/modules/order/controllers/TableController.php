<?php

namespace app\modules\order\controllers;

use app\Entity\Table\Pagination;
use app\modules\order\models\Orders;
use app\repositories\OrdersRepository;
use Yii;
use yii\web\Controller;

class TableController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @param null $status
     * @return string
     */
    public function actionIndex($statusSlug = null): string
    {
        $limit = 10;
        $params = Yii::$app->request->queryParams;
        $statusId = null;

        if ($statusSlug) {
            $statusList = Orders::getStatusList();

            $statusMap = array_flip(array_map(function($name) {
                return strtolower(str_replace(' ', '', $name));
            }, $statusList));

            $statusId = $statusMap[$statusSlug] ?? null;
        }

        $params['status'] = $statusId;

        $params['page'] = $params['page'] ?? 1;
        $params['limit'] = $params['limit'] ?? $limit;

        $amount = OrdersRepository::getAmountOrders($params);

        return $this->render('orders', [
            'orders' => OrdersRepository::getOrders($params),
            'columns' => OrdersRepository::getColumns(),
            'status' => $statusSlug,
            'pages' => (new Pagination($amount, $params['page'], $limit))->generatePages(),
            'rowStart' => 1,
            'rowEnd' => $limit,
            'total' => $amount,
        ]);
    }

}
