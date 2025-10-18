<?php

namespace App\Controllers;
use SpotifyWebAPI;

class Migrate extends BaseController
{

    public function index(): string
    {

        $count = 0;
        $msg = '';

        // Connect to the database, and retrieve all Goodreads entries with the old format.
        $db = db_connect();
        $GoodreadsModel = new \Midair\Goodreads\Models\Goodreads();
        $oldGoodreads = $GoodreadsModel->asObject()->like('guid', 'Review')->findAll();

        // Loop through the old Goodreads entries and update them to the new format.
        foreach ($oldGoodreads as $oldGoodread) {

            // Create a new Goodreads entry with the new format.
            $newGoodread = [];

            // Re-request the review from Goodreads and extract the parts we need to complete the model.
            $bookReviewPage = file_get_contents($oldGoodread->link);

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

            $newGoodread['title'] = $title;
            $newGoodread['author'] = $author;
            $newGoodread['rating'] = $rating;
            $newGoodread['description'] = $description;
            $newGoodread['content'] = $description;
            $newGoodread['image'] = $image;
            $newGoodread['book_description'] = $book_description;
            $newGoodread['publication_date'] = $publication_date;
            $newGoodread['guid'] = str_replace('Review', '', $oldGoodread->guid);
            $GoodreadsModel->update($oldGoodread->id, $newGoodread);

            // Also update the corresponding entry in the midair table.
            $MidairModel = new \App\Models\Midair();
            $MidairModel->where('url', str_replace('Review', '', $oldGoodread->guid))->set([
                'title' => $title,
                'excerpt' => $description,
                'content' => $description,
            ])->update();

            $count++;
        }

        log_message('info', "Import completed - added $count new entries.");
        if ($count > 0) {
            $msg .= "$count Goodreads reviews reformatted for new design.\n\n";
        }

        // Grab all Letterboxd entries that need to be updated.
        $LetterboxdModel = new \Midair\Letterboxd\Models\Letterboxd();
        $oldLetterboxds = $LetterboxdModel->asObject()->like('title', '★')->orLike('title', '½')->findAll();

        // Loop through the old Letterboxd entries and update them to the new format.
        foreach ($oldLetterboxds as $oldLetterboxd) {

            // Create a new Letterboxd entry with the new format.
            $newLetterboxd = [];

            // Extract the title, year of release and rating from the current title field.
            preg_match('/^(.+?)\s*,\s*(\d{4})\s*-\s*([★½]+)$/u', $oldLetterboxd->title, $matches);
            $newLetterboxd['title'] = $matches[1] ?? '';
            $newLetterboxd['release_year'] = $matches[2] ?? '';
            $newLetterboxd['rating'] = $matches[3] ?? '';

            // Move the review from description (if it exists).
            $newLetterboxd['review'] = '';
            if (!str_contains($oldLetterboxd->description, 'Watched on')) {
                $newLetterboxd['review'] = $oldLetterboxd->description;
            }

            // Load the entry, find the movie link, and extract the description and image.
            $filmDetailsPage = file_get_contents(preg_replace(
                ['/letterboxd\.com\/[^\/]+\/film\//', '/\/\d+\/$/'],
                ['letterboxd.com/film/', '/'],
                $oldLetterboxd->link
            ));
            preg_match('/"image":"https:\/\/(.+?)"/', $filmDetailsPage, $matches);
            $newLetterboxd['image'] = 'https://' . $matches[1] ?? '';
            preg_match('/og:description\" content=\"(.+?)\"/', $filmDetailsPage, $matches);
            $newLetterboxd['description'] = $matches[1] ?? '';

            // Strip content.
            $newLetterboxd['content'] = '';

            // Update the entry in the database.
            $LetterboxdModel->update($oldLetterboxd->id, $newLetterboxd);

            // Also update the corresponding entry in the midair table.
            $MidairModel = new \App\Models\Midair();
            $MidairModel->where('title', $newLetterboxd['title'] . ', ' . $newLetterboxd['release_year'] . ' - ' . $newLetterboxd['rating'])->set([
                'title' => $newLetterboxd['title'],
                'excerpt' => $newLetterboxd['review'],
                'content' => '',
            ])->update();

            $count++;
        }

        log_message('info', "Import completed - added $count new entries.");
        if ($count > 0) {
            $msg .= "$count Letterboxd reviews reformatted for new design.\n\n";
        }

        // Get the Spotify API environment variables.
        // Throw an error if not set.
        $spotify_api_url = env('spotify.client_id');
        if (!$spotify_api_url) {
            throw new \Exception('API client ID not set in environment variables.');
            return 'API client ID not set in environment variables.';
        }
        $session = new SpotifyWebAPI\Session(
            env('spotify.client_id'),
            env('spotify.client_secret'),
        );
        
        // Connect to the Spotify API and obtain credentials.
        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($accessToken);

        // Grab all Spotify track entries that need to have their data enriched.
        $SpotifyModel = new \Midair\Spotify\Models\Spotify();
        $oldSpotify = $SpotifyModel->asObject()->where('release_date', NULL)->findAll();

        // Loop through the old Spotify entries and update them to the new format.
        foreach ($oldSpotify as $oldSpotify) {

            // Create a new Spotify entry with the new format.
            $newSpotify = [];

            // Retrieve the album information from the Spotify API to get the release date, track listing and copyright.
            $albumDetails = $api->getAlbum(str_replace('https://open.spotify.com/album/', '', $oldSpotify->url));
        
            $track_listing = '';
            foreach($albumDetails->tracks->items as $track) {
                $track_listing .= $track->name . ', ';
            }
            $newSpotify['copyright'] = $albumDetails->copyrights[0]->text;
            $newSpotify['tracks'] = substr($track_listing, 0, -2);
            $newSpotify['release_date'] = $albumDetails->release_date;

            // Update the entry in the database.
            $SpotifyModel->update($oldSpotify->id, $newSpotify);

            // Also update the corresponding entry in the midair table.
            $MidairModel = new \App\Models\Midair();
            $MidairModel->where('title', $oldSpotify->track)->where('type', 'spotify')->set([
                'url' => $oldSpotify->guid,
            ])->update();

            $count++;
        }

        log_message('info', "Import completed - added $count new entries.");
        if ($count > 0) {
            $msg .= "$count Spotify tracks reformatted for new design.\n\n";
        }

        return $msg;

    }

}
