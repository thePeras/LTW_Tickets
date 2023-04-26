<?php

declare(strict_types=1);

class FAQ
{

    public readonly int $id;

    public readonly string $createdByUser;

    public readonly string $title;

    public readonly string $content;


    public function __construct(int $_id, string $_createdByUser,
        string $_title, string $_content
    ) {
        $this->id            = $_id;
        $this->createdByUser = $_createdByUser;
        $this->title         = $_title;
        $this->content       = $_content;

    }


}
