<?php

namespace Midair\Spotify\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'spotify')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'spotify')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('consuming', [
            'title' => 'Spotify tracks',
            'type' => 'consuming',
            'search' => 'spotify',
            'description' => 'Loved tracks on Spotify, in reverse chronological order.',
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
       $SpotifyModel = new \Midair\Spotify\Models\Spotify();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM spotify WHERE guid = ?';
       $spotify = $db->query($sql, [$url])->getRow();

        // If the blog doesn't exist, throw a 404 error.
        if (empty($spotify)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Spotify\Views\single', [
            'data' => $spotify,
            'title' => $spotify->track,
            'description' => $spotify->track . ' by ' . $spotify->artist . ' from ' . $spotify->album . ', loved on Spotify.',
            'type' => 'consuming',
            'search' => 'spotify',
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Spotify-single-' . $spotify->id,
        ]);
    }
}
