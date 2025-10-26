<?php

$routes->group('backloggd', ['namespace' => 'Midair\Backloggd\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index');
    $routes->get('(:num)', 'Display::index/$1');
    $routes->get('(:any)', 'Display::single/$1');
    $routes->get('/', 'Display::index');
});
