<?php

$routes->group('goodreads', ['namespace' => 'Midair\Goodreads\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
