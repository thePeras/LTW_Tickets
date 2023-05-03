<?php
declare(strict_types=1);


function get_database()
{
    $dbPath = "database/tickets.db";
    if (is_file($dbPath) === true) {
        return new PDO("sqlite:".$dbPath);
    }

    $pdo        = new PDO("sqlite:".$dbPath);
    $schemaFile = fopen("database/create.sql", "r");
    $schema     = fread($schemaFile, filesize("database/create.sql"));

    $pdo->exec($schema);
    return $pdo;

}
