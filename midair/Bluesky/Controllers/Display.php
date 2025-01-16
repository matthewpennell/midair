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

        // Retrieve all entries from the relevant table.
        $items = $MidairModel->asObject()->orderBy('date', 'DESC')->findAll();

        // Build the HTML output of the items feed.
        $content = '';
        foreach ($items as $item)
        {
            $content .= view('Midair\\' . ucfirst($item->type) . '\Views\item', [
                'data' => $item,
            ], [
                'cache' => 60,
                'cache_name' => ucfirst($item->type) . '-item-' . $item->id,
            ]);
        }

        // Load the main content view and pass in the data.
        return view('content', [
            'content' => $content,
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
        ], [
            'cache' => 60,
            'cache_name' => 'Bluesky-single-' . $bluesky->id,
        ]);
    }
}
