<?php

namespace Midair\Medium\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'medium')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'medium')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('writing', [
            'title' => 'Published on Medium',
            'type' => 'writing',
            'search' => 'medium',
            'description' => 'Posts that were originally published on Medium.com.',
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
       $MediumModel = new \Midair\Medium\Models\Medium();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM mediums WHERE link REGEXP ?';
       $pattern = $url . '$';
       $medium = $db->query($sql, [$pattern])->getRow();

        // If the post doesn't exist, throw a 404 error.
        if (empty($medium)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Medium\Views\single', [
            'data' => $medium,
            'title' => $medium->title,
            'type' => 'writing',
            'search' => 'medium',
            'description' => $medium->description,
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Medium-single-' . $medium->id,
        ]);
    }
}
