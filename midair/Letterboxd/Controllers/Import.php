<?php

namespace Midair\Letterboxd\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import entries from RSS feed.
     */
    public function index() {

        // Get the RSS feed URL from the environment variables.
        // Throw an error if it's not set.
        $rss_feed_url = env('letterboxd.rss_feed_url');
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
        $LetterboxdModel = new \Midair\Letterboxd\Models\Letterboxd();
        $MidairModel = new \App\Models\Midair();
        $newLetterboxdsCount = 0;

        // Loop through the RSS feed items and save them to the database.
        foreach ($rss->channel->item as $item) {

            // Check whether this entry already exists in the database.
            $existingLetterboxd = $LetterboxdModel->where('guid', $item->guid)->first();

            if (empty($existingLetterboxd)) {

                // If the entry doesn't exist, insert it into the database.
                $link = (string) $item->link;
                $guid = (string) $item->guid;
                $pubDate = (string) $item->pubDate;

                // Extract the title and year from the title. The title is in the format "Film Title, Year - ★★★½".
                preg_match('/^(.+?), (\d{4}) - (.+)$/', $item->title, $matches);
                $title = $matches[1] ?? '';
                $releaseYear = $matches[2] ?? '';
                $rating = $matches[3] ?? '';

                // Extract the poster image and the review text from the RSS description.
                // The poster image is the src attribute of the first <img> tag in the description, and the review text is the content of the second <p> tag, as long as it doesn't start with "Watched on".
                $description = (string) $item->description;
                preg_match('/<img src="(.+?)"/', $description, $matches);
                $image = $matches[1] ?? '';
                preg_match_all('/<p>(.+?)<\/p>/', $description, $matches);
                $review = '';
                if (isset($matches[1][1]) && !str_starts_with($matches[1][1], 'Watched on')) {
                    $review = $matches[1][1];
                }

                /**
                 * 24/02/26: Letterboxd are blocking direct requests to the review page,
                 * so commenting out that part of the code for now. Will need to find a 
                 * workaround for this if we want to fetch the review text and film metadata, 
                 * which is currently only available on the review page and not in the RSS feed.
                 *

                // Fetch the review page and get the contents of the review and link to the actual film.
                $client = \Config\Services::curlrequest();
                $response = $client->request('GET', $link, [
                    'allow_redirects' => true,
                    'headers' => [
                        'User-Agent' => 'Code Igniter Framework v4',
                        'Accept'     => 'text/html,application/xhtml+xml'
                    ]
                ]);
                $reviewPage = $response->getBody();
                preg_match('/og:description\" content=\"(.+?)\"/', $reviewPage, $matches);
                $review = $matches[1] ?? '';

                // Fetch the film details page and read additional metadata from there.
                $filmDetailsPage = file_get_contents(preg_replace(
                    ['/letterboxd\.com\/[^\/]+\/film\//', '/\/\d+\/$/'],
                    ['letterboxd.com/film/', '/'],
                    $link
                ));
                preg_match('/"image":"https:\/\/(.+?)"/', $filmDetailsPage, $matches);
                $image = 'https://' . $matches[1] ?? '';
                preg_match('/og:title\" content=\"(.+?)\"/', $filmDetailsPage, $matches);
                $title = $matches[1] ?? '';
                preg_match('/og:description\" content=\"(.+?)\"/', $filmDetailsPage, $matches);
                $description = $matches[1] ?? '';

                // split the title into the film name and release year.
                $title_parts = explode('(', $title);
                $title = $title_parts[0];
                $releaseYear = explode(')', $title_parts[1]);
                $releaseYear = $releaseYear[0];

                //*/

                $data = array(
                    'title' => $title,
                    'release_year' => $releaseYear,
                    'link' => $link,
                    'review' => $review,
                    'rating' => $rating,
                    //'description' => $description,
                    'image' => $image,
                    'guid' => $guid,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                );

                $LetterboxdModel->insert($data);
                $newLetterboxdsCount++;
                log_message('info', 'Inserted new Letterboxd entry: ' . $title);

                // Insert the new entry into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => $guid,
                    'source' => $link,
                    'excerpt' => $review,
                    'type' => 'letterboxd',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new Letterboxd entry into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newLetterboxdsCount new entries.");
        if ($newLetterboxdsCount > 0) {
            echo "$newLetterboxdsCount new Letterboxd entries imported.\n";
        }

    }

}
