<?php

declare(strict_types=1);


$ifHtml = function (bool $condition, string $true, string $false) : string {
    if ($condition === true) {
        return $true;
    }

    return $false;

};
