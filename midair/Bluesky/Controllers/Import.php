<?php

namespace Midair\Bluesky\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import blueskys from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('bluesky.rss_feed_url');
        if (!$rss_feed_url) {
            throw new \Exception('RSS feed URL not set in environment variables.');
            return;
        }

        // Load the RSS feed.
        $rss = simplexml_load_file($rss_feed_url);
        if ($rss === false) {
            throw new \Exception('Failed to load RSS feed.');
            return;
        }

        // Connect to the database and instantiate the relevant models.
        $db = db_connect();
        $BlueskyModel = new \Midair\Bluesky\Models\Bluesky();
        $MidairModel = new \App\Models\Midair();
        $newBlueskysCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this skeet already exists in the database.
            $existingBluesky = $BlueskyModel->where('guid', $item->guid)->first();

            if (empty($existingBluesky)) {

                // If the skeet doesn't exist, insert it into the database.
                $title = (string) $item->title;
                $link = (string) $item->link;
                $description = (string) $item->description;
                $author = (string) $item->author;
                $creator = (string) $item->children('dc', true)->creator;
                $guid = (string) $item->guid;
                $pubDate = (string) $item->pubDate;
                $content = (string) $item->children('content', true)->encoded;

                // Loop through all <category> elements and create a comma-separated string.
                $categories = [];
                foreach ($item->category as $category) {
                    $categories[] = (string) $category;
                }
                $categoriesString = implode(', ', $categories);

                $data = array(
                    'title' => $title,
                    'link' => $link,
                    'description' => $description,
                    'author'=> $author ? $author : $creator,
                    'categories' => $categoriesString,
                    'guid' => $guid,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'content' => $content,
                );

                $BlueskyModel->insert($data);
                $newBlueskysCount++;
                log_message('info', 'Inserted new skeet: ' . $title);

                // Insert the new skeet into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => str_replace([env('bluesky.rss_link_root'), '/'], ['', ''], $link), // strip the root URL and trailing slash
                    'source' => $link,
                    'excerpt' => $description,
                    'content' => $content,
                    'type' => 'bluesky',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new skeet into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newBlueskysCount new skeets.");
        echo "Import completed - added $newBlueskysCount new skeets.";

    }

}
