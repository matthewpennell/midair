<?php

namespace Midair\Flashfiction\Controllers;

use App\Controllers\BaseController;

class Display extends BaseController
{

    public $per_page = 10;

    public function index($page = 1): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve a total count of posts to use for pagination calculations.
        $total_count = $MidairModel->where('status', 'published')->where('type', 'flashfiction')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'flashfiction')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('writing', [
            'title' => 'Flash Fiction',
            'type' => 'writing',
            'search' => 'flashfiction',
            'description' => 'Flash fiction short stories, listed in reverse chronological order.',
            'articles' => $articles,
            'total_count' => $total_count,
            'per_page' => $this->per_page,
            'page' => $page,
        ]);
    }

    public function single($url = ''): string
    {
       // Connect to the database and instantiate the relevant models.
       $db = db_connect();
       $FlashfictionModel = new \Midair\Flashfiction\Models\Flashfiction();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM flashfiction WHERE link REGEXP ?';
       $pattern = '^' . env('flashfiction.rss_link_root') . $url . '$';
       $flashfiction = $db->query($sql, [$pattern])->getRow();

        // If the post doesn't exist, throw a 404 error.
        if (empty($flashfiction)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Flashfiction\Views\single', [
            'data' => $flashfiction,
            'title' => $flashfiction->title,
            'type' => 'writing',
            'search' => 'flashfiction',
            'description' => $flashfiction->description,
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Flashfiction-single-' . $flashfiction->id,
        ]);
    }
}
