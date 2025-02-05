<?php

$routes->group('strava', ['namespace' => 'Midair\Strava\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
    $routes->get('(:any)', 'Display::single/$1', ['priority' => 1]);
    $routes->get('/', 'Display::index', ['priority' => 1]);
});
