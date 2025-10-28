<?php

$routes->group('commonplace', ['namespace' => 'Midair\Commonplace\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index');
    $routes->get('(\d{1,3})', 'Display::index/$1');
    $routes->get('(:any)', 'Display::single/$1');
    $routes->get('/', 'Display::index');
});
