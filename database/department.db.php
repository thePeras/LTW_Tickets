<?php

declare(strict_types = 1);

class Department
{

    public readonly string $name;

    public readonly string $description;


    public function __construct(string $_name, string $_description)
    {
        $this->name        = $_name;
        $this->description = $_description;

    }


}
