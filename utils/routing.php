<?php

declare(strict_types=1);

require_once __DIR__."/logger.php";


function handle_api_route(string $path, string $method, Closure $handler) : void
{
    //append to regex the url parms and the / after the string
    $path = (substr($path, 0, (strlen($path) - 1))."\/?(?:\?.*)?$/");
    //match only the uri after
    if (preg_match($path, substr($_SERVER["REQUEST_URI"], 4)) === 1) {
        if ($_SERVER["REQUEST_METHOD"] === $method) {
            $handler();
            exit();
        }
    }

}


function no_api_route() : void
{
    log_to_stdout($_SERVER["REQUEST_METHOD"]." - ".$_SERVER["REQUEST_URI"]." has no api handler on ".__FILE__);
    http_response_code(403);
    echo '{"error":"no suitable api route handler..."}';
    exit();

}
