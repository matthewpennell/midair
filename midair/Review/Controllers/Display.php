<?php

namespace Midair\Review\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'review')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'review')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('writing', [
            'title' => 'Reviews',
            'type' => 'writing',
            'search' => 'review',
            'description' => 'Very occasional reviews of the art I consume.',
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
       $ReviewModel = new \Midair\Review\Models\Review();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM reviews WHERE link REGEXP ?';
       $pattern = '^' . env('review.rss_link_root') . $url . '$';
       $review = $db->query($sql, [$pattern])->getRow();

        // If the post doesn't exist, throw a 404 error.
        if (empty($review)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Review\Views\single', [
            'data' => $review,
            'title' => $review->title,
            'type' => 'writing',
            'search' => 'review',
            'description' => $review->description,
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Review-single-' . $review->id,
        ]);
    }
}
