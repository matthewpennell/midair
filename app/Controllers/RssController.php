<?php

namespace App\Controllers;

class RssController extends BaseController
{
    public function feed(string $types): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Split the types into an array and filter out any empty values.
        $types = array_filter(explode(',', $types));

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
