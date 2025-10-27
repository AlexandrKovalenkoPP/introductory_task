<?php

namespace order\components\Table;

use Yii;

class ColumnsHeader
{
    const int COLUMN_STRING = 0;
    const int COLUMN_DROPDOWN = 1;

    /** @var string $header Название колонки */
    public string $header;

    /** @var string $key Ключ колонки */
    public string $key;

    /** @var int $type Тип колонки */
    public int $type;

    public array|null $list = null;

    public string $category;

    /**
     * @param string $key
     * @param string $category
     * @param int $type
     * @param array|null $list
     */
    public function __construct(
        string $key,
        string $category,
        int $type,
        array|null $list = null,
    )
    {
        $this->key = $key;
        $this->category = $category;
        $this->header = Yii::t($category, ucfirst($key));
        $this->type = $type;
        $this->list = $list;
    }
}