<?php

namespace Midair\Strava\Models;

use CodeIgniter\Model;

class Strava extends Model
{
    protected $table      = 'strava';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'link', 'description', 'author', 'categories', 'guid', 'pubDate', 'content'];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $allowCallbacks = true;
}
