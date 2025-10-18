<?php

namespace App\Controllers;

class Search extends BaseController
{

    public function index(): string
    {
        // Pick up search query and section(s) from querystring.
        $q = $this->request->getGet('q');
        $section = $this->request->getGet('section');

        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel
            ->asObject()
            ->where('status', 'published')
            ->whereIn('type', explode(',', $section))
            ->groupStart()
            ->like('title', $q)
            ->orLike('excerpt', $q)
            ->orLike('content', $q)
            ->groupEnd()
            ->orderBy('date', 'DESC')
            ->findAll();

        // Load the main content view and pass in the data.
        return view('search', [
            'title' => 'Search results',
            'type' => 'search',
            'q' => $q,
            'search' => $section,
            'description' => 'Search results for "' . $q . '".',
            'articles' => $articles,
        ]);
    }

}
