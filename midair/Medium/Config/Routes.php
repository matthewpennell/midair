<?php

$routes->group('medium', ['namespace' => 'Midair\Medium\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index');
    $routes->get('(:num)', 'Display::index/$1');
    $routes->get('(:any)', 'Display::single/$1');
    $routes->get('/', 'Display::index');
});
