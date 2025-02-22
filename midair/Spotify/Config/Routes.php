<?php

$routes->group('spotify', ['namespace' => 'Midair\Spotify\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
