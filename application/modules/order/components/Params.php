<?php

namespace order\components;

use order\models\Orders;
use order\models\Services;

/**
 * Параметры для страницы с Заказами
 */
class Params
{
    /** @var int|null $status Статус заказа {@see Orders::getStatusList()} */
    public int|null $status = null;

    /** @var int|null $search Поисковая строка */
    public string|null $search = null;

    /** @var int|null $searchType Тип поиска */
    public string|null $searchType = null;

    /** @var int|null $service Тип сервиса заказа {@see Services} */
    public int|null $service = null;

    /** @var int|null $mode Мод заказа {@see Orders::getModeList()} */
    public int|null $mode = null;

    /** @var int $page Страница пагинации */
    public int $page = 1;
}