<?php

namespace Midair\Review\Models;

use CodeIgniter\Model;

class Review extends Model
{
    protected $table      = 'reviews';
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
