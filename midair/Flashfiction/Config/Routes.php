<?php

$routes->group('flashfiction', ['namespace' => 'Midair\Flashfiction\Controllers'], static function ($routes) {
    $routes->cli('import', 'Import::index', ['priority' => 1]);
});
