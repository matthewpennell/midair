<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Dynamic RSS feed creator.
$routes->get('rss/([a-zA-Z,]+)', 'RssController::feed/$1');
$routes->addRedirect('rss', 'rss/blog', 301);

// Handle any static pages on the site.
$routes->view('about', 'static/about');
$routes->view('contact', 'static/contact');
