<?php

namespace Midair\Commonplace\Controllers;

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
       $items = $MidairModel->asObject()->where('type', 'commonplace')->orderBy('date', 'DESC')->limit($per_page, $offset)->findAll();

       // Build the HTML output of the items feed.
       $content = '';
       foreach ($items as $item)
       {
           $content .= view('Midair\Commonplace\Views\item', [
               'data' => $item,
           ], [
               'cache' => 3600, // cache view for 1 hour
               'cache_name' => 'Commonplace-item-' . $item->id,
           ]);
       }

       // Load the main content view and pass in the data.
       $view = ($p > 1) ? 'page' : 'content';
       return view($view, [
           'content' => $content,
           'show_next' => count($items),
           'next_page' => $p + 1,
       ]);
    }

    public function single($url = ''): string
    {
       // Connect to the database and instantiate the relevant models.
       $db = db_connect();
       $CommonplaceModel = new \Midair\Commonplace\Models\Commonplace();

       // Retrieve the item from the database.
       $commonplace = $CommonplaceModel->asObject()->like('link', env('commonplace.rss_link_root') . $url)->first();

        // If the item doesn't exist, throw a 404 error.
        if (empty($commonplace)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load the main content view and pass in the data.
        return view('Midair\Commonplace\Views\single', [
            'data' => $commonplace,
            'title' => $commonplace->title,
        ], [
            'cache' => 3600, // cache view for 1 hour
            'cache_name' => 'Commonplace-single-' . $commonplace->id,
        ]);
    }
}
