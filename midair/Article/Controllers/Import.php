<?php

namespace Midair\Article\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import articles from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('article.rss_feed_url');
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
        $ArticleModel = new \Midair\Article\Models\Article();
        $MidairModel = new \App\Models\Midair();
        $newArticlesCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this article already exists in the database.
            $existingArticle = $ArticleModel->where('guid', $item->guid)->first();

            if (empty($existingArticle)) {

                // If the article doesn't exist, insert it into the database.
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

                $ArticleModel->insert($data);
                $newArticlesCount++;
                log_message('info', 'Inserted new article: ' . $title);

                // Insert the new article into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => str_replace([env('article.rss_link_root'), '/'], ['', ''], $link), // strip the root URL and trailing slash
                    'source' => $link,
                    'excerpt' => $description,
                    'content' => $content,
                    'type' => 'article',
                );

                $MidairModel->insert($data);

                log_message('info', 'Inserted new article into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newArticlesCount new articles.");
        echo "Import completed - added $newArticlesCount new articles.";

    }

}
