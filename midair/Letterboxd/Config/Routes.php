<?php

$routes->group('letterboxd', ['namespace' => 'Midair\Letterboxd\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
