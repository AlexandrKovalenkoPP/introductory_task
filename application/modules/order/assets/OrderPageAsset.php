<?php

namespace app\modules\order\assets;

use yii\web\AssetBundle;

class OrderPageAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/order/assets';
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap.min.css',
        'css/custom.css',
    ];

    public $js = [
        'js/jquery.min.js',
        'js/bootstrap.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}