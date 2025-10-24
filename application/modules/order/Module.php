<?php

namespace app\modules\order;

use yii\base\Module as BaseModule;

/**
 * Класс модуля "Order"
 */
class Module extends BaseModule
{
    /** @var string Имя категории для переводов модуля Order */
    public const string I18N_CATEGORY = 'order-module';

    /** @var string $controllerNamespace */
    public $controllerNamespace = 'app\modules\order\controllers';
}