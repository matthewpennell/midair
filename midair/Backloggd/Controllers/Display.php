<?php

namespace Midair\Backloggd\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'backloggd')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'backloggd')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('consuming', [
            'title' => 'Backloggd entries',
            'type' => 'consuming',
            'search' => 'backloggd',
            'description' => 'Backloggd gaming entries, in reverse chronological order.',
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
       $BackloggdModel = new \Midair\Backloggd\Models\Backloggd();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM backloggd WHERE guid = "' . $url . '"';
       $backloggd = $db->query($sql)->getRow();

        // If the blog doesn't exist, throw a 404 error.
        if (empty($backloggd)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Backloggd\Views\single', [
            'data' => $backloggd,
            'title' => $backloggd->title,
            'description' => $backloggd->title . ', a game recorded on Backloggd.',
            'type' => 'consuming',
            'search' => 'backloggd',
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Backloggd-single-' . $backloggd->id,
        ]);
    }
}
