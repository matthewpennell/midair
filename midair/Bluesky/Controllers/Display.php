<?php

namespace Midair\Bluesky\Controllers;

use App\Controllers\BaseController;

class Display extends BaseController
{
    public function index(): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Check for a page query parameter.
        $p = (int) $this->request->getGet('p');
        if (! $p) {
            $p = 1;
        }
        $per_page = 10;
        $offset = ($p * $per_page) - $per_page;

        // Retrieve all entries from the relevant table.
        $items = $MidairModel->asObject()->where('type', 'bluesky')->orderBy('date', 'DESC')->limit($per_page, $offset)->findAll();

        // Build the HTML output of the items feed.
        $content = '';
        foreach ($items as $item)
        {
            $content .= view('Midair\Bluesky\Views\item', [
                'data' => $item,
            ], [
                'cache' => 60,
                'cache_name' => 'Bluesky-item-' . $item->id,
            ]);
        }

        // Load the main content view and pass in the data.
        $view = ($p > 1) ? 'page' : 'content';
        return view($view, [
            'content' => $content,
            'title' => 'Bluesky posts',
            'show_next' => count($items),
            'next_page' => $p + 1,
        ]);
    }

    public function single($url = ''): string
    {
       // Connect to the database and instantiate the relevant models.
       $db = db_connect();
       $BlueskyModel = new \Midair\Bluesky\Models\Bluesky();

       // Retrieve the bluesky from the database.
       $bluesky = $BlueskyModel->asObject()->like('link', env('bluesky.rss_link_root') . $url)->first();

        // If the bluesky doesn't exist, throw a 404 error.
        if (empty($bluesky)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Bluesky\Views\single', [
            'data' => $bluesky,
            'title' => 'Bluesky post from ' . date('jS M Y', strtotime($bluesky->pubDate)),
        ], [
            'cache' => 60,
            'cache_name' => 'Bluesky-single-' . $bluesky->id,
        ]);
    }
}
