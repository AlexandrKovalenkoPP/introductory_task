<?php

namespace app\components\Table;

class ColumnsHeader
{
    const int COLUMN_STRING = 0;
    const int COLUMN_DROPDOWN = 1;

    /** @var string $header Название колонки */
    public string $header;

    /** @var int $type Тип колонки */
    public int $type;

    /**
     * @param string $header
     * @param int $type
     */
    public function __construct(
        string $header,
        int $type,
    )
    {
        $this->header = $header;
        $this->type = $type;
    }
}