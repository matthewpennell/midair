<?php

namespace Midair\Goodreads\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'goodreads')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'goodreads')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('consuming', [
            'title' => 'Goodreads reviews',
            'type' => 'consuming',
            'search' => 'goodreads',
            'description' => 'Published Goodreads reviews, in reverse chronological order.',
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
       $GoodreadsModel = new \Midair\Goodreads\Models\Goodreads();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM goodreads WHERE guid = ?';
       $goodreads = $db->query($sql, [$url])->getRow();

        // If the blog doesn't exist, throw a 404 error.
        if (empty($goodreads)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Goodreads\Views\single', [
            'data' => $goodreads,
            'title' => $goodreads->title,
            'description' => $goodreads->title . ' by ' . $goodreads->author . ', rated and reviewed on Goodreads.',
            'type' => 'consuming',
            'search' => 'goodreads',
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Goodreads-single-' . $goodreads->id,
        ]);
    }
}
