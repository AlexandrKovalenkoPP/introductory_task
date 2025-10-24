<?php

namespace app\modules\order\repositories;

use app\modules\order\models\Orders;
use app\modules\order\Module;
use Yii;

class ModesRepository
{
    public static function getModesForFilter(): array
    {
        return [
            (object) [
                'id' => null,
                'tag' => Yii::t(Module::I18N_CATEGORY, 'all'),
                ],
            (object) [
                'id' => Orders::MODE_MANUAL,
                'tag' => Yii::t(Module::I18N_CATEGORY, Orders::getModeList()[Orders::MODE_MANUAL])
                ],
            (object) [
                'id' => Orders::MODE_AUTO,
                'tag' => Yii::t(Module::I18N_CATEGORY, Orders::getModeList()[Orders::MODE_AUTO])
                ]
        ];
    }
}