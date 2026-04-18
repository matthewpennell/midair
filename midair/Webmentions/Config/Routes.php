<?php

$routes->get('webmention', 'Receive::index', ['namespace' => 'Midair\Webmentions\Controllers']);
$routes->post('webmention', 'Receive::index', ['namespace' => 'Midair\Webmentions\Controllers']);
$routes->post('webmention/manual', 'Receive::manual', [
    'namespace' => 'Midair\Webmentions\Controllers',
    'filter'    => 'csrf',
]);
$routes->get('webmention/send', 'Send::index', ['namespace' => 'Midair\Webmentions\Controllers']);
$routes->post('webmention/send', 'Send::submit', ['namespace' => 'Midair\Webmentions\Controllers']);
$routes->get('webmention/admin', 'Admin::index', ['namespace' => 'Midair\Webmentions\Controllers']);
