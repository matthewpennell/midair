<?php

namespace Midair\Backloggd\Controllers;

use App\Controllers\BaseController;

class Import extends BaseController {

    /**
     * Import entries from Playing page.
     */
    public function index() {

        // Get the Playing page URL from the environment variables.
        // Throw an error if it's not set.
        $playing_page_url = env('backloggd.playing_page_url');
        if (!$playing_page_url) {
            throw new \Exception('Playing page URL not set in environment variables.');
            return;
        }

        // Load the Playing page. Need to strip whitespace from the start of the response.
        $file = file_get_contents($playing_page_url);
        if ($file === false) {
            throw new \Exception('Failed to load Playing page.');
            return;
        }

        // Connect to the database and instantiate the relevant models.
        $db = db_connect();
        $BackloggdModel = new \Midair\Backloggd\Models\Backloggd();
        $MidairModel = new \App\Models\Midair();
        $newBackloggdsCount = 0;

        // Find the list of games.
        preg_match_all('/<a[^>]+href="([^"]+)" class="cover-link"/', $file, $matches);

        // Loop through each game in the list.
        foreach ($matches[1] as $match) {
            preg_match('~^/games/([^/]+)/?$~', $match, $name);
            $gameName = $name[1] ?? '';
            $existingBackloggd = $BackloggdModel->where('guid', $gameName)->first();
            // If the game has not already been added to the database
            if (empty($existingBackloggd)) {
                // Fetch the individual game page and read additional metadata from there.
                $file = file_get_contents('https://backloggd.com/games/' . $gameName . '/');
                if ($file === false) {
                    throw new \Exception('Failed to load Game page.');
                    return;
                }
                $guid = (string) $gameName;
                $link = 'https://backloggd.com/games/' . $gameName . '/';
                $pubDate = date('Y-m-d H:i:s');

                // Retrieve the game's name.
                preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $file, $matches);
                $title = trim(html_entity_decode(strip_tags($matches[1])));

                // Retrieve the game's description.
                preg_match('/<meta\s+name="description"\s+content="([^"]*)"/i', $file, $matches);
                $description = trim($matches[1]);

                // Retrieve the game's image.
                preg_match('/<meta\s+itemprop="image"\s+content="([^"]*)"/i', $file, $matches);
                $image = trim($matches[1]);

                // Retrieve the game's release date.
                preg_match_all('/<a\s[^>]*href="[^"]*\/games\/lib\/popular\/release_year[^"]*"[^>]*>(.*?)<\/a>/is', $file, $matches);
                $release_date = trim(html_entity_decode(strip_tags($matches[1][1])));

                // Retrieve the game's genres.
                preg_match_all('/<a\s[^>]*href="[^"]*\/games\/lib\/popular\/genre[^"]*"[^>]*>(.*?)<\/a>/is', $file, $matches);
                $uniqueGenres = array_unique($matches[1] ?? []);
                $genres = implode(', ', array_map(function($genre) {
                    return trim(html_entity_decode(strip_tags($genre)));
                }, $uniqueGenres));

                $data = array(
                    'title' => $title,
                    'link' => $link,
                    'description' => $description,
                    'genres' => $genres,
                    'guid' => $guid,
                    'release_date' => $release_date,
                    'image' => $image,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                );

                $BackloggdModel->insert($data);
                $newBackloggdsCount++;
                log_message('info', 'Inserted new Backloggd entry: ' . $title);

                // Insert the new entry into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $title,
                    'url' => $guid,
                    'source' => $link,
                    'excerpt' => $description,
                    'type' => 'backloggd',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new Backloggd entry into main stream table.');

            }
        }


        log_message('info', "Import completed - added $newBackloggdsCount new entries.");
        if ($newBackloggdsCount > 0) {
            echo "$newBackloggdsCount new Backloggd entries imported.\n";
        }

    }

}
