<?php

namespace app\modules\order\controllers;

use app\modules\order\models\Orders;
use app\repositories\OrdersRepository;
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
    public function actionIndex($statusSlug = null): string
    {
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

        $result = (new OrdersRepository())->setParams($params)->query()->result();

        return $this->render('orders', [
            'result' => $result,
            'status' => $statusSlug,
            'pages' => new Pagination(['totalCount' => $result->total]),
        ]);
    }

}
