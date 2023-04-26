<?php

declare(strict_types=1);

class Ticket
{

    public readonly int $id;

    public readonly string $title;

    public readonly string $description;

    public readonly string $status;

    public readonly string $hashtags;

    public readonly string $assignee;

    public readonly string $createdByUser;

    public readonly string $department;


    public function __construct($id, string $title, string $description, string $status,
        string $hashtags, string $assignee, string $createdByUser, string $department
    ) {
        $this->id            = $id;
        $this->title         = $title;
        $this->description   = $description;
        $this->status        = $status;
        $this->hashtags      = $hashtags;
        $this->assignee      = $assignee;
        $this->createdByUser = $createdByUser;
        $this->department    = $department;

    }


}
