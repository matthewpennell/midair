<?php

namespace Midair\Webmentions\Controllers;

use App\Controllers\BaseController;
use Midair\Webmentions\Models\Webmention;

class Admin extends BaseController
{
    public function index(): string
    {
        $model = new Webmention();
        $mentions = $model->asObject()->orderBy('created_at', 'DESC')->findAll();

        return view('Midair\Webmentions\Views\admin', [
            'title'    => 'Webmentions Admin',
            'type'     => 'webmentions',
            'mentions' => $mentions,
        ]);
    }
}
