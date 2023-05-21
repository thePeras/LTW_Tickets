<?php

declare(strict_types=1);

class Label
{

    public string $label;

    public string $color;

    public string $backgroundColor;

    public int $createdAt;


    public function __construct(string $_label, string $_color, string $_backgroundColor,
        int $_createdAt
    ) {
        $this->label           = $_label;
        $this->color           = $_color;
        $this->backgroundColor = $_backgroundColor;
        $this->createdAt       = $_createdAt;

    }


}
