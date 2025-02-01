<?php

namespace App\Models;

use CodeIgniter\Model;

class Midair extends Model
{
    protected $table            = 'midair';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['date', 'title', 'url', 'source', 'excerpt', 'content', 'status', 'type'];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $allowCallbacks = true;
}
