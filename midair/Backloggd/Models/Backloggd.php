<?php

namespace Midair\Backloggd\Models;

use CodeIgniter\Model;

class Backloggd extends Model
{
    protected $table      = 'backloggd';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'link', 'description', 'genres', 'guid', 'pubDate', 'release_date', 'image'];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $allowCallbacks = true;
}
