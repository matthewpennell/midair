<?php

namespace App\Controllers;

class RssController extends BaseController
{
    /**
     * Get the available feed types
     * 
     * @return array
     */
    private function getFeedTypes(): array
    {
        return [
            'blog' => 'Blog posts',
            'medium' => 'Medium posts',
            'review' => 'Reviews blog entries',
            'flashfiction' => 'Flash fiction posts',
            'commonplace' => 'Commonplace entries',
            'spotify' => 'New Spotify favourites',
            'goodreads' => 'Goodreads additions/reviews',
            'letterboxd' => 'New Letterboxd watches/reviews',
        ];
    }

    public function index(): string
    {
        return view('feeds', [
            'type' => 'rss',
            'feed_types' => $this->getFeedTypes(),
        ]);
    }

    public function feed($types = null): string
    {
        $types = explode(',', $types);

        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve the relevant page of entries from the relevant table.
        $items = $MidairModel->asObject()->where('status', 'published')->whereIn('type', $types)->orderBy('date', 'DESC')->limit(25)->findAll();

        // Build the RSS feed content.
        $feed = view('rss', [
            'items' => $items,
            'title' => 'matthewpennell.com RSS feed',
            'description' => 'UK-based UX designer and developer with over two decades of experience and a passion for writing, music, and gaming.',
            'last_build_date' => date(DATE_RSS, strtotime($items[0]->created_at ?? 'now')),
        ]);
        $this->response->setContentType('application/rss+xml');

        return $feed;
    }

}
