<?php

namespace App\Controllers;

class Home extends BaseController
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

        // Retrieve the relevant page of entries from the relevant table.
        $items = $MidairModel->asObject()->orderBy('date', 'DESC')->limit($per_page, $offset)->findAll();

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
        $view = ($p > 1) ? 'page' : 'content';
        return view($view, [
            'content' => $content,
            'show_next' => count($items),
            'next_page' => $p + 1,
        ]);
    }
}
