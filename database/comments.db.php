<?php

declare(strict_types=1);


class Comment
{

    public readonly int $id;

    public readonly string $content;

    public readonly string $createdByUser;

    public readonly int $ticket;


    public function __construct(int $id, string $content,
        string $createdByUser, int $ticket
    ) {
        $this->id            = $id;
        $this->content       = $content;
        $this->createdByUser = $createdByUser;
        $this->ticket        = $ticket;

    }


}
