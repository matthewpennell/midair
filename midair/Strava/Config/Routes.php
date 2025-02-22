<?php

$routes->group('strava', ['namespace' => 'Midair\Strava\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
