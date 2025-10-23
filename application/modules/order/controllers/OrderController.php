<?php

namespace app\modules\order\controllers;

use app\Entity\Table\Pagination;
use app\modules\order\models\Orders;
use app\repositories\OrdersRepository;
use Yii;
use yii\web\Controller;

class OrderController extends Controller
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
    public function actionIndex($status = null): string
    {
        $limit = 10;
        $params = Yii::$app->request->queryParams;

        if ($status !== null) {
            $params['status'] = $status;
        }

        $params['page'] = $params['page'] ?? 1;
        $params['limit'] = $params['limit'] ?? $limit;

        $amount = OrdersRepository::getAmountOrders($params);

        return $this->render('orders', [
            'orders' => OrdersRepository::getOrders($params),
            'columns' => OrdersRepository::getColumns(),
            'status' => $status,
            'pages' => (new Pagination($amount, $params['page'], $limit))->generatePages(),
            'rowStart' => 1,
            'rowEnd' => $limit,
            'total' => $amount,
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
