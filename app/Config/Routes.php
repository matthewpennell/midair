<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Main::index');
$routes->get('writing/(:num)', 'Main::writing/$1');
$routes->get('writing', 'Main::writing');
$routes->get('consuming/(:num)', 'Main::consuming/$1');
$routes->get('consuming', 'Main::consuming');
$routes->get('search', 'Search::index');

// One-off migration of old data structures to new.
$routes->cli('migrate', 'Migrate::index');

// Dynamic RSS feed creator
$routes->get('rss/([a-zA-Z,]+)', 'RssController::feed/$1');

// Handle query string format and redirect to path format
$routes->get('rss', function() {
    $request = service('request');
    $types = $request->getGet('types');
    
    if (empty($types)) {
        return redirect()->to('rss/blog,commonplace,flashfiction,medium,review', 301);
    }
    
    // Handle both array and string types
    $typesArray = is_array($types) ? $types : [$types];
    $typesArray = array_filter($typesArray, 'is_string');
    $typesArray = array_map('trim', $typesArray);
    $typesArray = array_filter($typesArray);
    
    if (empty($typesArray)) {
        return redirect()->to('rss/blog,commonplace,flashfiction,medium,review', 301);
    }
    
    return redirect()->to('rss/' . implode(',', $typesArray), 301);
});

$routes->get('feeds', 'RssController::index');

// Handle any static pages on the site.
$routes->get('about-me', static function () {
    return view('static/about-me', [
        'type' => 'about',
    ]);
});
$routes->get('work', static function () {
    return view('static/work', [
        'type' => 'work',
    ]);
});
$routes->get('now', static function () {
    return view('static/now', [
        'type' => 'now',
    ]);
});
$routes->get('contact', static function () {
    return view('static/contact', [
        'type' => 'contact',
    ]);
});
$routes->get('colophon', static function () {
    return view('static/colophon', [
        'type' => 'colophon',
    ]);
});
$routes->get('accessibility-statement', static function () {
    return view('static/accessibility-statement', [
        'type' => 'a11y',
    ]);
});
