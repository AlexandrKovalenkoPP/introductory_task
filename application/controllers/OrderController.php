<?php

namespace app\controllers;

use app\models\Orders;
use app\repositories\OrdersRepository;
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
     * @return string
     */
    public function actionIndex($status = null)
    {
        $repository = OrdersRepository::getOrders($status);

        // Передаем провайдер во вью
        return $this->render('orders', [
            'data' => $repository,
        ]);
    }

    public function actionPending()
    {
        return $this->actionIndex(Orders::STATUS_PENDING);
    }

    public function actionProgress()
    {
        return $this->actionIndex(Orders::STATUS_IN_PROGRESS);
    }

    public function actionCompleted()
    {
        return $this->actionIndex(Orders::STATUS_COMPLETED);
    }

    public function actionCancelled()
    {
        return $this->actionIndex(Orders::STATUS_CANCELED);
    }

    public function actionFail()
    {
        return $this->actionIndex(Orders::STATUS_FAIL);
    }

}
