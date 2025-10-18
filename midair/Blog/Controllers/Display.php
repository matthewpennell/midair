<?php

namespace Midair\Blog\Controllers;

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
        $total_count = $MidairModel->where('status', 'published')->where('type', 'blog')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'blog')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('writing', [
            'title' => 'Blog',
            'type' => 'writing',
            'search' => 'blog',
            'description' => 'The blog archive, containing stuff I\'ve written on this site and its predecessors over the years. Not everything, but most of the recent stuff at least.',
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
       $BlogModel = new \Midair\Blog\Models\Blog();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM blog WHERE link REGEXP ?';
       $pattern = '^' . env('blog.rss_link_root') . '[0-9]{4}/[0-9]{2}/[0-9]{2}/' . $url . '/$';
       $blog = $db->query($sql, [$pattern])->getRow();

        // If the blog doesn't exist, throw a 404 error.
        if (empty($blog)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Blog\Views\single', [
            'data' => $blog,
            'title' => $blog->title,
            'type' => 'writing',
            'description' => $blog->description,
            'search' => 'blog',
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Blog-single-' . $blog->id,
        ]);
    }
}
