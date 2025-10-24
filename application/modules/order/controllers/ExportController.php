<?php

namespace app\modules\order\controllers;

use app\modules\order\models\Orders;
use app\modules\order\repositories\OrdersRepository;
use Yii;
use yii\web\Controller;

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

    public function actionExportCsv()
    {
        $params = Yii::$app->request->queryParams;

        $query = OrdersRepository::getExportQuery($params);

        $statusList = Orders::getStatusList();
        $modeList = Orders::getModeList();

        $fileName = 'orders_report_' . date('Ymd_His') . '.csv';
        $headers = [
            'ID', 'Пользователь', 'Ссылка', 'Количество',
            'Сервис', 'Статус', 'Дата создания', 'Режим'
        ];

        $stream = fopen('php://temp', 'r+');

        fwrite($stream, "\xEF\xBB\xBF");

        fputcsv($stream, $headers, ';');

        foreach ($query->batch(1000) as $rows) {
            foreach ($rows as $order) {

                $statusName = $statusList[$order[Orders::getLocationStatus()]] ?? 'N/A';
                $modeName = $modeList[$order[Orders::getLocationMode()]] ?? 'N/A';

                $dataRow = [
                    $order[Orders::getLocationId()],
                    $order[Orders::getLocationUser()],
                    $order[Orders::getLocationLink()],
                    $order[Orders::getLocationQuantity()],
                    $order[Orders::getLocationServiceId()], // Предполагаем, что здесь уже имя сервиса
                    $statusName,
                    $order[Orders::getLocationCreatedAt()], // Предполагаем, что здесь уже отформатированная дата/время
                    $modeName,
                ];

                fputcsv($stream, $dataRow, ';');
            }
        }

        rewind($stream);

        return Yii::$app->response->sendStreamAsFile($stream, $fileName, [
            'mimeType' => 'text/csv',
            'inline' => false,
        ]);
    }

}
