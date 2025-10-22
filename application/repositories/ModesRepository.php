<?php

namespace app\repositories;

use app\models\Orders;
use yii\helpers\Html;

class ModesRepository
{
    public static function getModes()
    {
        return [
            Orders::MODE_MANUAL,
            Orders::MODE_AUTO
        ];
    }
}