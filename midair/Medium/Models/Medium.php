<?php

namespace Midair\Medium\Models;

use CodeIgniter\Model;

class Medium extends Model
{
    protected $table      = 'mediums';
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
