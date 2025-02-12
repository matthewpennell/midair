<?php

namespace Midair\Commonplace\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import items from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('commonplace.rss_feed_url');
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
        $CommonplaceModel = new \Midair\Commonplace\Models\Commonplace();
        $MidairModel = new \App\Models\Midair();
        $newCommonplacesCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this item already exists in the database.
            $existingCommonplace = $CommonplaceModel->where('guid', $item->guid)->first();

            if (empty($existingCommonplace)) {

                // If the item doesn't exist, insert it into the database.
                $title = (string) $item->title;
                $link = (string) $item->link;
                $description = (string) $item->description;
                $author = (string) $item->author;
                $creator = (string) $item->children('dc', true)->creator;
                $guid = (string) $item->guid;
                $pubDate = (string) $item->pubDate;
                $content = (string) $item->children('content', true)->encoded;

                // If there's no content, use the description (which contains the full article)
                // and create a shorter excerpt from first three sentences.
                if ($content == '') {
                    $content = $description;
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
                    'link' => $link,
                    'description' => $description,
                    'author'=> $author ? $author : $creator,
                    'categories' => $categoriesString,
                    'guid' => $guid,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'content' => $content,
                );

                $CommonplaceModel->insert($data);
                $newCommonplacesCount++;
                log_message('info', 'Inserted new flash fiction: ' . $title);

                // Insert the new item into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => str_replace([env('commonplace.rss_link_root'), '/'], ['', ''], $link), // strip the root URL and trailing slash
                    'source' => $link,
                    'excerpt' => $description,
                    'content' => $content,
                    'type' => 'commonplace',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new commonplace item into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newCommonplacesCount new commonplace items.");
        echo "Import completed - added $newCommonplacesCount new commonplace items.";

    }

}
