<?php

namespace App\Controllers;

class Main extends BaseController
{

    public $per_page = 10;

    public function index(): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve the latest entries from the Writing and Consuming feeds, and the most recent Bluesky post.
        $latest_writing = $MidairModel->asObject()->where('status', 'published')->whereIn('type', ['blog', 'medium', 'flashfiction', 'review'])->orderBy('date', 'DESC')->first();
        $latest_consuming = $MidairModel->asObject()->where('status', 'published')->whereIn('type', ['goodreads', 'commonplace', 'letterboxd', 'backloggd', 'spotify'])->orderBy('date', 'DESC')->first();
        $latest_bluesky = $MidairModel->asObject()->where('status', 'published')->where('type', 'bluesky')->orderBy('date', 'DESC')->first();

        // Load the main content view and pass in the data.
        $view = 'home';
        return view($view, [
            'title' => 'Home',
            'type' => 'home',
            'description' => 'The personal blog and portfolio of a UK-based UX designer and developer with over two decades of experience and a passion for writing, music, and gaming.',
            'latest_writing' => $latest_writing,
            'latest_consuming' => $latest_consuming,
            'latest_bluesky' => $latest_bluesky,
        ]);
    }

    public function writing($page = 1): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve a total count of posts to use for pagination calculations.
        $total_count = $MidairModel->where('status', 'published')->whereIn('type', ['blog', 'medium', 'flashfiction', 'review'])->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->whereIn('type', ['blog', 'medium', 'flashfiction', 'review'])->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('writing', [
            'title' => 'Writing',
            'type' => 'writing',
            'search' => 'blog,medium,flashfiction,review',
            'description' => 'The blog archive, containing stuff I\'ve written here and elsewhere over the years. Not everything, but most of the recent stuff at least.',
            'articles' => $articles,
            'total_count' => $total_count,
            'per_page' => $this->per_page,
            'page' => $page,
        ]);
    }

    public function consuming($page = 1): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve a total count of posts to use for pagination calculations.
        $total_count = $MidairModel->where('status', 'published')->whereIn('type', ['goodreads', 'letterboxd', 'backloggd', 'spotify', 'commonplace'])->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Consuming feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->whereIn('type', ['goodreads', 'letterboxd', 'backloggd', 'spotify', 'commonplace'])->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('consuming', [
            'title' => 'Consuming',
            'type' => 'consuming',
            'search' => 'goodreads,letterboxd,backloggd,spotify,commonplace',
            'description' => 'Everything I am reading, watching, listening to or playing.',
            'articles' => $articles,
            'total_count' => $total_count,
            'per_page' => $this->per_page,
            'page' => $page,
        ]);
    }
}
