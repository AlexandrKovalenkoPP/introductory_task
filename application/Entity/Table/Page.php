<?php

namespace app\Entity\Table;

class Page
{
    public int $id;
    public string $title;
    public bool $isActive;

    public function __construct(
        int $id,
        string $title,
        bool $isActive,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->isActive = $isActive;
    }
}