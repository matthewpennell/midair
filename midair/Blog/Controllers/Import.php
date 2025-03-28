<?php

namespace Midair\Blog\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import blogs from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('blog.rss_feed_url');
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
        $BlogModel = new \Midair\Blog\Models\Blog();
        $MidairModel = new \App\Models\Midair();
        $newBlogsCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this blog already exists in the database.
            $existingBlog = $BlogModel->where('guid', $item->guid)->first();

            if (empty($existingBlog)) {

                // If the blog doesn't exist, insert it into the database.
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

                $BlogModel->insert($data);
                $newBlogsCount++;
                log_message('info', 'Inserted new blog: ' . $title);

                // Extract the post name from a standard Wordpress post URL.
                $pattern = "/^https?:\/\/[^\/]+\/\d{4}\/\d{2}\/\d{2}\/([^\/]+)\/?$/";
                preg_match($pattern, $link, $matches);

                // Insert the new blog into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => $matches[1],
                    'source' => $link,
                    'excerpt' => $description,
                    'content' => $content,
                    'type' => 'blog',
                );

                $MidairModel->insert($data);

                log_message('info', 'Inserted new blog into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newBlogsCount new blogs.");
        if ($newBlogsCount > 0) {
            echo "$newBlogsCount new blog articles imported.\n";
        }
 
    }

}
