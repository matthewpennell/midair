<?php

namespace Midair\Commonplace\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'commonplace')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'commonplace')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('consuming', [
            'title' => 'Commonplace entries',
            'type' => 'consuming',
            'search' => 'commonplace',
            'description' => 'Published commonplace entries, in reverse chronological order.',
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
       $CommonplaceModel = new \Midair\Commonplace\Models\Commonplace();

       // Retrieve the item from the database.
       $commonplace = $CommonplaceModel->asObject()->where('link', env('commonplace.rss_link_root') . $url)->first();

        // If the item doesn't exist, throw a 404 error.
        if (empty($commonplace)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Commonplace\Views\single', [
            'data' => $commonplace,
            'title' => $commonplace->title,
            'description' => $commonplace->description,
            'type' => 'consuming',
            'search' => 'commonplace',
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Commonplace-single-' . $commonplace->id,
        ]);
    }
}
