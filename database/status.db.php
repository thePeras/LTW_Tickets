<?php

declare(strict_types=1);

class Status
{

    public string $status;

    public string $color;

    public string $backgroundColor;

    public int $createdAt;


    public function __construct(string $_status, string $_color, string $_backgroundColor,
        int $_createdAt
    ) {
        $this->status          = $_status;
        $this->color           = $_color;
        $this->backgroundColor = $_backgroundColor;
        $this->createdAt       = $_createdAt;

    }


}
