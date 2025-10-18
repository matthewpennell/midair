<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInfoToLetterboxd extends Migration
{
    public function up()
    {
        $fields = [
            'release_year' => ['type' => 'INT', 'after' => 'description'],
            'review' => ['type' => 'TEXT', 'after' => 'release_year'],
            'image' => ['type' => 'TEXT', 'after' => 'review'],
            'rating' => ['type' => 'VARCHAR', 'constraint' => 10, 'after' => 'image'],
        ];
        $this->forge->addColumn('letterboxd', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('letterboxd', 'release_year');
        $this->forge->dropColumn('letterboxd', 'review');
        $this->forge->dropColumn('letterboxd', 'image');
        $this->forge->dropColumn('letterboxd', 'rating');
    }
}
