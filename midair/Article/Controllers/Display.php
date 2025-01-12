<?php

namespace Midair\Article\Controllers;

use App\Controllers\BaseController;

class Display extends BaseController
{
    public function index(): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve all entries from the relevant table.
        $items = $MidairModel->asObject()->orderBy('date', 'DESC')->findAll();

        // Build the HTML output of the items feed.
        $content = '';
        foreach ($items as $item)
        {
            $content .= view('Midair\\' . ucfirst($item->type) . '\Views\item', [
                'data' => $item,
            ], [
                'cache' => 60,
                'cache_name' => ucfirst($item->type) . '-item-' . $item->id,
            ]);
        }

        // Load the main content view and pass in the data.
        return view('content', [
            'content' => $content,
        ]);
    }

    public function single($url = ''): string
    {
       // Connect to the database and instantiate the relevant models.
       $db = db_connect();
       $ArticleModel = new \Midair\Article\Models\Article();

       // Retrieve the article from the database.
       $article = $ArticleModel->asObject()->like('link', env('article.rss_link_root') . $url)->first();

        // If the article doesn't exist, throw a 404 error.
        if (empty($article)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Article\Views\single', [
            'data' => $article,
        ], [
            'cache' => 60,
            'cache_name' => 'Article-single-' . $article->id,
        ]);
    }
}
