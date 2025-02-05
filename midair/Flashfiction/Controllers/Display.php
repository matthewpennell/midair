<?php

namespace Midair\Flashfiction\Controllers;

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
       $FlashfictionModel = new \Midair\Flashfiction\Models\Flashfiction();

       // Retrieve the item from the database.
       $flashfiction = $FlashfictionModel->asObject()->like('link', env('flashfiction.rss_link_root') . $url)->first();

        // If the item doesn't exist, throw a 404 error.
        if (empty($flashfiction)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Flashfiction\Views\single', [
            'data' => $flashfiction,
        ], [
            'cache' => 60,
            'cache_name' => 'Flashfiction-single-' . $flashfiction->id,
        ]);
    }
}
