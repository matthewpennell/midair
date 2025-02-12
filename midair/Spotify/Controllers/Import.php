<?php

namespace Midair\Spotify\Controllers;

use App\Controllers\BaseController;
use SpotifyWebAPI;

class Import extends BaseController {

    /**
     * Import entries from RSS feed.
     */
    public function index() {

        // Get the Spotify API environment variables.
        // Throw an error if not set.
        $spotify_api_url = env('spotify.client_id');
        if (!$spotify_api_url) {
            throw new \Exception('API client ID not set in environment variables.');
            return;
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

        // First request the playlist and read the total property to find how many tracks are currently in the playlist.
        $playlistTracks = $api->getPlaylistTracks(env('spotify.playlist_id'), [
            'fields' => 'total',
        ]);
        $playlistLength = $playlistTracks->total;

        // Now request the 25 most recently added tracks.
        $playlistTracks = $api->getPlaylistTracks(env('spotify.playlist_id'), [
            'fields' => 'items(added_at,track(id,name,artists(name),album(href,images(url),name,external_urls(spotify)))',
            'limit' => 25,
            'offset' => $playlistLength - 25,
        ]);

        // Connect to the database and instantiate the relevant models.
        $db = db_connect();
        $SpotifyModel = new \Midair\Spotify\Models\Spotify();
        $MidairModel = new \App\Models\Midair();
        $newSpotifysCount = 0;

        // Loop through the returned tracks and insert any new ones into the database.
        foreach($playlistTracks->items as $item) {

            // Check whether this track already exists in the database.
            $existingSpotify = $SpotifyModel->where('guid', $item->track->id)->first();

            if (empty($existingSpotify)) {

                // If the track doesn't exist, insert it into the database.
                $track = (string) $item->track->name;
                $artist = (string) $item->track->artists[0]->name;
                $album = (string) $item->track->album->name;
                $cover = (string) $item->track->album->images[0]->url;
                $url = (string) $item->track->album->external_urls->spotify;
                $guid = (string) $item->track->id;
                $pubDate = (string) $item->added_at;

                $data = array(
                    'track' => $track,
                    'artist' => $artist,
                    'album' => $album,
                    'cover'=> $cover,
                    'url' => $url,
                    'guid' => $guid,
                    'pubDate' => date('Y-m-d H:i:s', strtotime($pubDate)),
                );

                $SpotifyModel->insert($data);
                $newSpotifysCount++;
                log_message('info', 'Inserted new Spotify track: ' . $track);

                // Insert the new entry into the main site stream table.
                $data = array(
                    'date' => date('Y-m-d H:i:s', strtotime($pubDate)),
                    'title' => $track,
                    'url' => $cover,
                    'source' => $url,
                    'excerpt' => $artist,
                    'content' => $album,
                    'type' => 'spotify',
                );

                $MidairModel->insert($data);
                log_message('info', 'Inserted new Spotify track into main stream table.');

            }

        }

        log_message('info', "Import completed - added $newSpotifysCount new tracks.");
        echo "Import completed - added $newSpotifysCount new tracks.";

    }

}
