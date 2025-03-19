<?php

namespace Midair\Medium\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import items from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('medium.rss_feed_url');
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
        $MediumModel = new \Midair\Medium\Models\Medium();
        $MidairModel = new \App\Models\Midair();
        $newMediumsCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this item already exists in the database.
            $existingMedium = $MediumModel->where('guid', $item->guid)->first();

            if (empty($existingMedium)) {

                // If the medium doesn't exist, insert it into the database.
                $title = (string) $item->title;
                $link = (string) $item->link;
                $description = (string) $item->description;
                $author = (string) $item->author;
                $creator = (string) $item->children('dc', true)->creator;
                $guid = (string) $item->guid;
                $pubDate = (string) $item->pubDate;
                $content = (string) $item->children('content', true)->encoded;

                // If there's no description, create one from first three sentences.
                if ($description == '') {
                    preg_match_all('/[^.!?]*[.!?]/', strip_tags($content), $matches);
                    $description = implode(' ', array_slice($matches[0], 0, 3));
                }

                // Loop through all <category> elements and create a comma-separated string.
                $categories = [];
                foreach ($item->category as $category) {
                    $categories[] = (string) $category;
                }
                $categoriesString = implode(', ', $categories);

                $data = array(
                    'title' => $title,
                    'link' => explode('?', $link)[0], // strip the querystring
                    'description' => $description,
                    'author'=> $author ? $author : $creator,
                    'categories' => $categoriesString,
                    'guid' => $guid,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'content' => $content,
                );

                $MediumModel->insert($data);
                $newMediumsCount++;
                log_message('info', 'Inserted new Medium piece: ' . $title);

                // Insert the new medium into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => str_replace(env('medium.rss_link_root'), '', end(...[explode('/', explode('?', $link)[0])])), // strip the root URL and querystring, and only use the last segment
                    'source' => explode('?', $link)[0], // strip the querystring
                    'excerpt' => $description,
                    'content' => $content,
                    'type' => 'medium',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new Medium item into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newMediumsCount new Medium items.");
        if ($newMediumsCount > 0) {
            echo "$newMediumsCount new Medium.com articles imported.\n";
        }

    }

}
