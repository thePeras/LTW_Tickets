<?php

$pagesDir = __DIR__.'/pages';
$exp      = "/\/([[:alpha:]]*)/";
$matches  = [];

if ($_SERVER["REQUEST_URI"] === "/") {
    include "pages/home.php";
} else if (preg_match($exp, $_SERVER["REQUEST_URI"], $matches) !== 0) {
    $fullpath = $pagesDir."/".$matches[1].".php";
    if (is_file($fullpath) === true) {
        include $fullpath;
    } else {
        include $pagesDir."/notFound.php";
    }
}
