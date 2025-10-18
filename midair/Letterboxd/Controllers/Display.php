<?php

namespace Midair\Letterboxd\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'letterboxd')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'letterboxd')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('consuming', [
            'title' => 'Letterboxd entries',
            'type' => 'consuming',
            'search' => 'letterboxd',
            'description' => 'Published Letterboxd entries, in reverse chronological order.',
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
       $LetterboxdModel = new \Midair\Letterboxd\Models\Letterboxd();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM letterboxd WHERE guid = ? OR WHERE link LIKE "' . env('letterboxd.rss_link_root') . $url . '%"';
       $letterboxd = $db->query($sql, [$url])->getRow();

        // If the blog doesn't exist, throw a 404 error.
        if (empty($letterboxd)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Letterboxd\Views\single', [
            'data' => $letterboxd,
            'title' => $letterboxd->title,
            'description' => $letterboxd->title . ', watched and rated on Letterboxd.',
            'type' => 'consuming',
            'search' => 'letterboxd',
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Letterboxd-single-' . $letterboxd->id,
        ]);
    }
}
