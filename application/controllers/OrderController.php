<?php

namespace app\controllers;

use app\repositories\Orders;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
    public function actionIndex()
    {
        // Создаем провайдер данных
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\Orders::find(),//Order::find(), // Выбираем все записи из модели Order
            'pagination' => [
                'pageSize' => 20, // Устанавливаем размер страницы
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC, // Сортируем по дате создания по убыванию
                ]
            ],
        ]);

        // Передаем провайдер во вью
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPending()
    {
        return Orders::getOrders('pending');
    }

    public function actionInProgress()
    {

    }

    public function actionCompleted()
    {

    }

    public function actionCancelled()
    {
        
    }

    public function actionError()
    {
        
    }

}
