<?php

namespace app\repositories;

use app\modules\order\models\Orders;

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