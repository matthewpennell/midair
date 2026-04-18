<?php

namespace Midair\Webmentions\Models;

use CodeIgniter\Model;

class Webmention extends Model
{
    protected $table      = 'webmentions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'source', 'target', 'author_name', 'author_url', 'author_photo',
        'content', 'mention_type',
    ];

    protected $useTimestamps = true;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $allowCallbacks     = true;
}
