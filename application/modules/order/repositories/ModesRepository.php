<?php

namespace order\repositories;

use order\components\Table\DropDownList;
use order\models\Orders;
use order\Module;
use Yii;

/**
 * Репозиторий получения списка модов для {@see DropDownList::dropDownList()}
 */
class ModesRepository
{
    /**
     * Список
     *
     * @return object[]
     */
    public static function getModesForFilter(): array
    {
        return [
            [
                'id' => null,
                'tag' => Yii::t(Module::I18N_CATEGORY, 'all'),
            ],
            [
                'id' => Orders::MODE_MANUAL,
                'tag' => Yii::t(Module::I18N_CATEGORY, Orders::getModeList()[Orders::MODE_MANUAL])
            ],
            [
                'id' => Orders::MODE_AUTO,
                'tag' => Yii::t(Module::I18N_CATEGORY, Orders::getModeList()[Orders::MODE_AUTO])
            ]
        ];
    }
}