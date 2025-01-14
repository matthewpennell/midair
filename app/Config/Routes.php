<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
$routes->get('/', 'ReactController::index');

// Handle any static pages on the site.
$routes->view('about', 'static/about');
$routes->view('contact', 'static/contact');
