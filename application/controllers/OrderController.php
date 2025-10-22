<?php

namespace app\controllers;

use app\models\Orders;
use app\repositories\OrdersRepository;
use stdClass;
use Yii;
use yii\web\Controller;

class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
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
    public function actionIndex($status = null): string
    {
        $params = Yii::$app->request->queryParams;

        if ($status !== null) {
            $params['status'] = $status;
        }

        $params['page'] = $params['page'] ?? 1;

        $orders = OrdersRepository::getOrders($params);
        $columns = OrdersRepository::getColumns();

        $pages = [];
        for ($i = 1; $i <= 10; $i++) {
            $page = new StdClass();
            $page->number = $i;
            $pages[] = $page;
        }

        $rowStart = 1;
        $rowEnd = 100;
        $total = 5000;

        return $this->render('orders', [
            'orders' => $orders,
            'columns' => $columns,
            'status' => $status,
            'pages' => $pages,
            'rowStart' => $rowStart,
            'rowEnd' => $rowEnd,
            'total' => $total,
        ]);
    }

    public function actionPending(): string
    {
        return $this->actionIndex(Orders::STATUS_PENDING);
    }

    public function actionInProgress(): string
    {
        return $this->actionIndex(Orders::STATUS_IN_PROGRESS);
    }

    public function actionCompleted(): string
    {
        return $this->actionIndex(Orders::STATUS_COMPLETED);
    }

    public function actionCancelled(): string
    {
        return $this->actionIndex(Orders::STATUS_CANCELED);
    }

    public function actionFail(): string
    {
        return $this->actionIndex(Orders::STATUS_FAIL);
    }

}
