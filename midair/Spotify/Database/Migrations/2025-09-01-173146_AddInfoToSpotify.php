<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInfoToSpotify extends Migration
{
    public function up()
    {
        $fields = [
            'release_date' => ['type' => 'TEXT', 'after' => 'album'],
            'tracks' => ['type' => 'TEXT', 'after' => 'release_date'],
            'copyright' => ['type' => 'TEXT', 'after' => 'tracks'],
        ];
        $this->forge->addColumn('spotify', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('spotify', 'release_date');
        $this->forge->dropColumn('spotify', 'tracks');
        $this->forge->dropColumn('spotify', 'copyright');
    }
}
