<?php

namespace Midair\Spotify\Models;

use CodeIgniter\Model;

class Spotify extends Model
{
    protected $table      = 'spotify';
    protected $primaryKey = 'id';
    protected $allowedFields = ['track', 'artist', 'album', 'cover', 'url', 'guid', 'pubDate', 'copyright', 'tracks', 'release_date'];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $allowCallbacks = true;
}
