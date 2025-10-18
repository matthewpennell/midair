<?php

namespace Midair\Letterboxd\Models;

use CodeIgniter\Model;

class Letterboxd extends Model
{
    protected $table      = 'letterboxd';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'link', 'description', 'author', 'categories', 'guid', 'pubDate', 'content', 'release_year', 'review', 'rating', 'image'];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $allowCallbacks = true;
}
