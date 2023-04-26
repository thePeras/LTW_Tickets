<?php

declare(strict_types=1);

class Change
{

    public readonly int $id;

    public readonly string $timestamp;

    public readonly string $user;


    public function __construct(int $id, string $timestamp, string $user)
    {
        $this->id        = $id;
        $this->timestamp = $timestamp;
        $this->user      = $user;

    }


}

class AssignedChange extends Change
{

    public readonly string $agent;


    public function __construct(int $id, string $timestamp, string $user, string $agent)
    {
        parent::__construct($id, $timestamp, $user);
        $this->$agent = $agent;

    }


}

class StatusChanges extends Change
{

    public readonly string $status;


    public function __construct(int $id, string $timestamp, string $user, string $status)
    {
        parent::__construct($id, $timestamp, $user);
        $this->status = $status;

    }


}
