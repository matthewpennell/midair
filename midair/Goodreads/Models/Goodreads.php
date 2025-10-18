<?php

namespace Midair\Goodreads\Models;

use CodeIgniter\Model;

class Goodreads extends Model
{
    protected $table      = 'goodreads';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'link', 'description', 'author', 'categories', 'guid', 'pubDate', 'content', 'book_description', 'publication_date', 'image', 'rating'];

    // Dates
    protected $useTimestamps = true;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $allowCallbacks = true;
}
