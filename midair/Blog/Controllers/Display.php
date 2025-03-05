<?php

namespace Midair\Blog\Controllers;

use App\Controllers\BaseController;

class Display extends BaseController
{
    public function index(): string
    {
       // Connect to the database.
       $db = db_connect();
       $MidairModel = new \App\Models\Midair();

       // Check for a page query parameter.
       $p = (int) $this->request->getGet('p');
       if (! $p) {
           $p = 1;
       }
       $per_page = 10;
       $offset = ($p * $per_page) - $per_page;

       // Retrieve all entries from the relevant table.
       $items = $MidairModel->asObject()->where('type', 'blog')->orderBy('date', 'DESC')->limit($per_page, $offset)->findAll();

       // Build the HTML output of the items feed.
       $content = '';
       foreach ($items as $item)
       {
           $content .= view('Midair\Blog\Views\item', [
               'data' => $item,
           ], [
               'cache' => 60,
               'cache_name' => 'Blog-item-' . $item->id,
           ]);
       }

       // Load the main content view and pass in the data.
       $view = ($p > 1) ? 'page' : 'content';
       return view($view, [
            'content' => $content,
            'title' => 'Blogs',
            'show_next' => count($items),
            'next_page' => $p + 1,
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
        ], [
            'cache' => 60,
            'cache_name' => 'Blog-single-' . $blog->id,
        ]);
    }
}
