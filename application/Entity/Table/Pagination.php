<?php

namespace app\Entity\Table;

use stdClass;

class Pagination
{
    public stdClass $period;
    public int $count;

    public function __construct(
        protected int $amount,
        protected int $currentPage,
        protected int $limit
    ) {
        $this->count = $amount % $limit;
        $this->getPeriod();
    }

    public function generatePages(): array
    {
        $pages = [];
        $pages[] = new Page(1, '&laquo;', false);

        for ($i = $this->period->start; $i <= $this->period->end; $i++) {
            $pages[] = new Page($i, $i, $this->currentPage == $i);
        }

        $pages[] = new Page($this->count, '&raquo;', false);

        return $pages;
    }

    public function getPeriod(): void
    {
        $this->period = new stdClass();
        $this->period->start = $this->currentPage > 5 ? $this->currentPage - 5 : 1;
        $this->period->end = 11;

        if ($this->currentPage > 5 && $this->currentPage < ($this->count - 5)) {
            $this->period->end = $this->currentPage + 5;
        } else if ($this->currentPage > 5 && $this->currentPage >= ($this->count - 5)) {
            $this->period->end = $this->count;
        }

    }
}