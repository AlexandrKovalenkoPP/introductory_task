<?php

namespace order\controllers;

use order\models\OrdersSearch;
use order\repositories\OrdersRepository;
use Yii;
use yii\data\Pagination;
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
    public function actionIndex(): string
    {
        Yii::$app->language = 'ru-RU';
        $params = Yii::$app->request->queryParams;

        $searchModel = new OrdersSearch();
        $searchModel->load($params, '');
        $result = (new OrdersRepository())->setParams($params)->query()->result();

        return $this->render('orders', [
            'result' => $result,
            'searchModel' => $searchModel,
            'pages' => new Pagination(['totalCount' => $result->total]),
            'tabs' => $searchModel->getTabs($this->id),
            'status' => $params['status'],
        ]);
    }

}
