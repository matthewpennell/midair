<?php

namespace Midair\Goodreads\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    private function fetchUrl(string $url): string|false {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language: en-GB,en;q=0.5',
                'Accept-Encoding: identity',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1',
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: none',
                'Sec-Fetch-User: ?1',
            ],
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0',
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($response !== false && $httpCode === 200) ? $response : false;
    }

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
        $file = $this->fetchUrl($rss_feed_url);
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

                // Look up the book in Open Library, keeping the RSS values if no match is found.
                $openLibraryUrl = 'https://openlibrary.org/search.json?' . http_build_query([
                    'title' => $title,
                    'author' => $author,
                ]);
                $openLibraryResponse = $this->fetchUrl($openLibraryUrl);
                $openLibraryResults = $openLibraryResponse !== false
                    ? json_decode($openLibraryResponse, true)
                    : null;
                $openLibraryBook = $openLibraryResults['docs'][0] ?? [];
                $openLibraryAuthor = $openLibraryBook['author_name'][0] ?? '';
                $openLibraryCoverId = $openLibraryBook['cover_i'] ?? null;
                $openLibraryKey = $openLibraryBook['key'] ?? '';
                $openLibraryCoverEditionKey = $openLibraryBook['cover_edition_key'] ?? '';
                $openLibraryTitle = $openLibraryBook['title'] ?? '';
                $publicationDate = $openLibraryBook['first_publish_year'] ?? '';
                $bookDescription = '';

                if ($openLibraryKey !== '') {
                    $workResponse = $this->fetchUrl('https://openlibrary.org' . $openLibraryKey . '.json');
                    $workData = $workResponse !== false
                        ? json_decode($workResponse, true)
                        : null;
                    $workDescription = $workData['description'] ?? '';
                    $bookDescription = is_array($workDescription)
                        ? ($workDescription['value'] ?? '')
                        : $workDescription;
                    $bookDescription = is_string($bookDescription) ? $bookDescription : '';
                    $bookDescription = trim(explode('---', $bookDescription, 2)[0]);
                }

                if ($openLibraryAuthor !== '') {
                    $author = $openLibraryAuthor;
                }
                if ($openLibraryTitle !== '') {
                    $title = $openLibraryTitle;
                }
                if ($openLibraryCoverId !== null) {
                    $image = 'https://covers.openlibrary.org/b/OLID/' . rawurlencode((string) $openLibraryCoverEditionKey) . '-M.jpg';
                }

                $data = array(
                    'title' => $title,
                    'link' => $link,
                    'description' => $description,
                    'content' => $description,
                    'book_description' => $bookDescription,
                    'publication_date' => $publicationDate,
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
