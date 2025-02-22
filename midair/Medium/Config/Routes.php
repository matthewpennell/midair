<?php

$routes->group('medium', ['namespace' => 'Midair\Medium\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
