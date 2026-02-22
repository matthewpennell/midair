<?php

namespace Midair\Goodreads\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import entries from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('goodreads.rss_feed_url');
        if (!$rss_feed_url) {
            throw new \Exception('RSS feed URL not set in environment variables.');
            return;
        }

        // Load the RSS feed. Need to strip whitespace from the start of the response.
        $file = trim(file_get_contents($rss_feed_url));
        $rss = simplexml_load_string($file, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($rss === false) {
            throw new \Exception('Failed to load RSS feed.');
            return;
        }

        // Connect to the database and instantiate the relevant models.
        $db = db_connect();
        $GoodreadsModel = new \Midair\Goodreads\Models\Goodreads();
        $MidairModel = new \App\Models\Midair();
        $newGoodreadssCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this entry already exists in the database.
            $existingGoodreads = $GoodreadsModel->where('guid', str_replace('Review', '', $item->guid))->first();

            // If the entry doesn't exist and it is a review, insert it into the database.
            if (empty($existingGoodreads) && strpos($item->guid, 'Review') === 0) {

                // If the entry doesn't exist, insert it into the database.
                $link = (string) $item->link;
                preg_match("/authorName.*>(.+)<\//", $item->description, $matches);
                $author = $matches[1] ?? '';
                preg_match("/bookTitle\" href=\"(.+)\"/", $item->description, $matches);
                $bookUrl = $matches[1] ?? '';
                preg_match("/bookTitle.*>(.+)<\//", $item->description, $matches);
                $title = $matches[1] ?? '';
                $guid = (string) str_replace('Review', '', $item->guid);
                $pubDate = (string) $item->pubDate;
                preg_match("/(\d) stars/", $item->description, $matches);
                $rating = $matches[1] ?? '';
                preg_match("/<img .* src=\"(.+)\"/", $item->description, $matches);
                $image = $matches[1] ?? '';

                // If there's no content, grab the description and chop out the metadata to 
                // only leave the review (if there is one).
                preg_match('/<br\/>\s*(.+)/', $item->description, $matches);
                $description = $matches[1] ?? '';

                // Loop through all <category> elements and create a comma-separated string.
                $categories = [];
                foreach ($item->category as $category) {
                    $categories[] = (string) $category;
                }
                $categoriesString = implode(', ', $categories);

                /**
                 * 22/02/26: Goodreads seems to have blocked fetching the contents of the individual book pages, 
                 * so I can no longer request the proper title, description, publication date, and image URL. 
                 * For now, I'll just pull what I can from the RSS feed and save that to the database, but ideally 
                 * I would like to be able to fetch the full review content and metadata from the book page. I'll 
                 * need to look into whether there's a way around this block in the future.
                 *
                
                // Request the review from Goodreads and extract the parts we need to complete the model.
                $bookReviewPage = file_get_contents($link);

                // Extract the title from the H1.
                preg_match('/<h1>(.+?)<\/h1>/', $bookReviewPage, $matches);
                $title = explode(' &gt; ', $matches[1] ?? '');
                $title = $title[count($title) - 1];

                // Extract the author name from the authorName link.
                preg_match('/<a[^>]*?class="authorName"[^>]*?>\s*<span[^>]*>([^<]+)<\/span>\s*<\/a>/i', $bookReviewPage, $matches);
                $author = $matches[1] ?? '';

                // Extract the review from the reviewText div.
                preg_match('/<div\s+class="[^"]*reviewText[^"]*"\s+[^>]*?>\s*(.+?)\s*<\/div>/is', $bookReviewPage, $matches);
                $description = $matches[1] ?? '';

                // Grab the rating.
                preg_match('/<div\s+class="rating"\s+itemprop="reviewRating"[^>]*>\s*<span\s+class="value-title"\s+title="([^"]+)"/', $bookReviewPage, $matches);
                $rating = $matches[1] ?? '';

                // Now request the actual book page and retrieve the remaining pieces of metadata.
                preg_match('/<a\s+[^>]*?class="[^"]*\bbookTitle\b[^"]*"[^>]*\bhref="([^"]+)"[^>]*>/i', $bookReviewPage, $matches);
                $bookUrl = $matches[1] ?? '';
                $bookDetailPage = file_get_contents('https://www.goodreads.com' . $bookUrl);

                // Pull the image URL out of the JavaScript metadata.
                preg_match('/"imageUrl":"([^"]+\.(?:jpg|jpeg|png|gif|webp))"/i', $bookDetailPage, $matches);
                $image = $matches[1] ?? '';

                // Pull the book description out of the JavaScript metadata.
                preg_match_all('/"description":"((?:[^"\\\]|\\\\.)*)"/i', $bookDetailPage, $matches);
                $book_description = $matches[1][1] ?? ''; // not sure if this will always be the second match...

                // Pull the publication date out of the JavaScript metadata.
                if (preg_match('/"publicationTime":(-?\d+)/', $bookDetailPage, $matches)) {
                    $timestamp = $matches[1] / 1000; // Convert to seconds
                    $formattedDate = date('F j, Y', $timestamp);
                }
                $publication_date = $formattedDate ?? '';

                */

                $data = array(
                    'title' => $title,
                    'link' => $link,
                    'description' => $description,
                    'content' => $description,
                    //'book_description' => $book_description,
                    //'publication_date' => $publication_date,
                    'image' => $image,
                    'author'=> $author,
                    'rating' => $rating,
                    'categories' => $categoriesString,
                    'guid' => $guid,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                );

                $GoodreadsModel->insert($data);
                $newGoodreadssCount++;
                log_message('info', 'Inserted new Goodreads entry: ' . $title);

                // Insert the new entry into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => str_replace([env('goodreads.rss_link_root'), '/'], ['', ''], $link), // strip the root URL and trailing slash
                    'source' => $link,
                    'excerpt' => $description,
                    'content' => $description,
                    'type' => 'goodreads',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new Goodreads entry into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newGoodreadssCount new entries.");
        if ($newGoodreadssCount > 0) {
            echo "$newGoodreadssCount new Goodreads reviews imported.\n";
        }

    }

}
