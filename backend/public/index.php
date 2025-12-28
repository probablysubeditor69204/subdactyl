<?php

// INTERNAL DEBUGGING
header('Content-Type: text/plain');
print_r([
    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'null',
    'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'null',
    'PATH_INFO' => $_SERVER['PATH_INFO'] ?? 'null',
    'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? 'null',
    'PHP_SELF' => $_SERVER['PHP_SELF'] ?? 'null',
]);
die();

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

require __DIR__.'/../vendor/autoload.php';

(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
