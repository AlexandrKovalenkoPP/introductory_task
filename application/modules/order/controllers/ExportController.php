<?php

namespace order\controllers;

use order\components\ExportCSV;
use order\repositories\OrdersRepository;
use Yii;
use yii\web\Controller;
use yii\web\RangeNotSatisfiableHttpException;

class ExportController extends Controller
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
