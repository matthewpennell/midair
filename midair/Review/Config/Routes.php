<?php

$routes->group('review', ['namespace' => 'Midair\Review\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
