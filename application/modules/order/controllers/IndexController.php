<?php

namespace order\controllers;

use order\components\ExportCSV;
use order\components\repositories\OrdersRepository;
use order\models\OrdersSearch;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\RangeNotSatisfiableHttpException;

class IndexController extends Controller
{
    public $layout = false;

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
    public function actionList(): string
    {
        Yii::$app->language = 'ru-RU';
        $params = Yii::$app->request->queryParams;

        $searchModel = new OrdersSearch();
        $searchModel->load($params, '');
        $result = (new OrdersRepository())->setParams($params)->query();

        return $this->render('orders', [
            'pages' => new Pagination([
                'totalCount' => $result->getTotal(),
                'pageSize' => $result->limit,
                'pageSizeParam' => false,
                'forcePageParam' => true,
            ]),
            'result' => $result->result(),
            'searchModel' => $searchModel,
            'tabs' => $searchModel->getTabs(),
            'status' => $params['status'] ?? null,
        ]);
    }

    /**
     * Экспорт данных таблицы с параметрами в CSV
     *
     * @throws RangeNotSatisfiableHttpException
     */
    public function actionExportOrdersFromTableCsv(): \yii\web\Response|\yii\console\Response
    {
        $repository = new OrdersRepository();
        $headers = ['id', 'user', 'link', 'quantity', 'service', 'status', 'created', 'mode'];
        $result = ExportCSV::exportFromQuery(
            'orders',
            $repository->setParams()->query()->query,
            $headers,
        );

        return Yii::$app->response->sendStreamAsFile(
            $result->stream,
            $result->fileName,
            [
                'mimeType' => 'text/csv',
                'inline' => false,
            ]
        );
    }

}
